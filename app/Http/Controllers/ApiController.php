<?php

namespace App\Http\Controllers;

use Dflydev\DotAccessData\Util;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiController extends BaseController
{
    //manifest
       public function manifest(Request $request)
    {
        $u = Utils::get_user($request);
        if ($u == null) {
            Utils::error("User not found");
        }
        $roles = DB::table('admin_role_users')
            ->where('user_id', $u->id)->get();
        $company = Company::find($u->company_id);
        $out_of_stock = DB::table('stock_records')
            ->where('company_id', $u->company_id)
           
            ->count();

        $data = [
            'name' => 'Inventory Track',
            'version' => '1.0.0',
            'company' => $company,
            'user_id' => $u->id,
            'user_name' => $u->name,
            'author' => 'Swabrah',
            'user_role' => $roles->pluck('role_id')->toArray(),
            'out_of_stock' => $out_of_stock

        ];
        Utils::success($data, "Manifest retrieved successfully");
    }


    //file uploading
      public function file_uploading(Request $request)
    {
       $path = Utils::file_upload($request->file('photo'));
       if($path == '') {
           Utils::error("File upload failed");
       }
       Utils::success([
           'file_name' => $path,
       ],  "File uploaded successfully");

    }
    // my list 
      public function my_list(Request $request, $m)
    {
        $u =Utils::get_user($request);
        if ($u == null) {
            Utils::error("User not found");
        }
        $m = "App\Models\\" . $m;
        $data =  $m::where('company_id', $u->company_id)->limit(1000000)->get();
        Utils::success($data, "Records retrieved successfully");
       
    }
    
    //update logic api & create
    public function my_update(Request $request, $m)
    {
        $u =Utils::get_user($request);
        if ($u == null) {
            Utils::error("User not found");
        }

        $m = "App\Models\\" . $m;
        $object = $m::find($request->id);

        $isEdit  = true;
        if ($object == null) {
            $object = new $m();
            $isEdit = false;
        }

        $table_name = $object->getTable();
        $columns = Schema::getColumnListing($table_name);
        $except = ['id', 'created_at', 'updated_at'];
        $data = $request->all();
        foreach ($data as $key => $value) {
            if (!in_array($key, $columns)) {
                continue;
            }
            if (in_array($key, $except)) {
                continue;
            }
            $object->$key = $value;
        }
        $object->company_id = $u->company_id; // Set the company_id from the logged-in user

        //temp_image_field
        if ($request->temp_image_field != null) {
            if (strlen($request->temp_image_field) > 1) {
                $file = $request->file('photo');
                if($file != null) {
                    $path = "";
                    try {
                    $path = Utils::file_upload($request->file('photo'));
                    } catch   (\Exception $e) {
                        $path = "";
                    }
                    if(strlen($path) > 3) {
                        $field_name = $request->temp_image_field;
                        $object->$field_name = $path;
                    }
                }
            }
           
        }

        try {
            $object->save();
        } catch (\Exception $e) {
            Utils::error("Error updating record: " . $e->getMessage());
        }
        $new_object = $m::find($object->id);
        if($isEdit){
            // If it's an edit, we can return the updated object
            Utils::success($new_object, "Record updated successfully");
        } else {
            // If it's a create, we can return the new object
            Utils::success($new_object, "Record created successfully");
        }

    }

    //register api/
    
    public function register(Request $request){
        if($request->firstname == null){
            Utils::error("First name is required");
        }
        if($request->lastname == null){
            Utils::error("Last name is required");
        }
        if($request->username == null){
            Utils::error("Username is required");
        }
        
        //check if password is provided
        if($request->password == null){
            Utils::error("Password is required");
        }

        //check if company name is provided
        if($request->company_name == null){
            Utils::error("Company name is required");
        }
        //check if currency is provided
        if($request->currency == null){
            Utils::error("Currency is required");
        }
            $new_user = new User();
            $new_user->firstname = $request->firstname;
            $new_user->lastname = $request->lastname;
            $new_user->username = $request->username;
            $new_user->password = password_hash($request->password,PASSWORD_DEFAULT);
            $new_user->name = $request->first_name . " " . $request->last_name;
            $new_user->company_id = 9;
            $new_user->phone1 = $request->phone1;
            $new_user->status = "Active";
            try{
                $new_user->save();
            } catch (\Exception $e) {
                Utils::error("Error saving user: " . $e->getMessage());
            }

            $registered_user = User::find($new_user->id);
            if($registered_user == null){
                Utils::error("User registration failed");
            }


             $company =  new Company();
            $company->Owner_id = $registered_user->id;
            $company->name = $request->company_name;
            $company->email = $request->email;
            $company->status = "Active";
            $company->expiry_license = date('Y-m-d', strtotime('+1 year'));
            $company->phone1 = $request->phone1;
            $company->currency = $request->currency;
            try {
                $company->save();
            } catch (\Exception $e) {
                Utils::error("Error saving company: " . $e->getMessage());
            }
            $registered_company = Company::find($company->id);
            if($registered_company == null){
                Utils::error("Company registration failed");
            }

            //DB insert into admin_role_user
            DB::table('admin_role_users')->insert([
                'user_id' => $registered_user->id,
                'role_id' => 2 ,// Assuming 2 is the ID for the admin role
                 'created_at' => now(),
                'updated_at' => now()
            ]);

            Utils::success([
                'user' => $registered_user,
                'company' => $registered_company
            ], "Registration successful");

    }  

    //login logic api
    
    public function login(Request $request){
        
        if($request->username == null){
            Utils::error("Username is required");
        }
        
        //check if password is provided
        if($request->password == null){
            Utils::error("Password is required");
        }
        $user = User::where('username', $request->username)->first();
        if($user == null){
            Utils::error("User not found");
        }

        if(!password_verify($request->password, $user->password)){
            Utils::error("Invalid password");
        }

        Utils::success($user, "Login successful");
    }

}

