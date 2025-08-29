<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utils 
{
    public static function file_upload($file){
        if ($file == null) {
            Utils::error("No file uploaded");
        }
        //get file extention
        $file_extension = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extension;
        $public_path = public_path() . '/storage/images';
        $file->move($public_path, $file_name);
        $url = 'images/' . $file_name;

        return $url;
    }


    public static function get_user(Request $request )
    {
         $logged_in_user_id = $request->header('logged_in_user_id');
         $u = User::find($logged_in_user_id);
         return $u;

        
    }
    public static function success($data , $message)
    {
        //set header response to json
       header('Content-Type: application/json');

       http_response_code(200);
      echo json_encode([
            'code' => 1,
            'message' => $message,
            'data' => $data
        ]);
        die();
    }
    public static function error($message, $code = 400)
    {
        //set header response to json
        header('Content-Type: application/json');

        http_response_code(200);
        echo json_encode([
            'code' => 0,
            'message' => $message,
        ]);
        die();
    }

    static function getActiveFinancialPeriod($company_id)
    {
        return FinancialPeriod::where('company_id', $company_id)
            ->where('status', 'active')
            ->first();
    }
}