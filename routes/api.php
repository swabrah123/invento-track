<?php

use App\Models\StockSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

//api routes
Route::post('auth/register', [ApiController::class, 'register']);
Route::post('auth/login', [ApiController::class, 'login']);
Route::post('api/{m}', [ApiController::class, 'my_update']);
Route::get('api/{m}', [ApiController::class, 'my_list']);
Route::post('file-uploading', [ApiController::class, 'file_uploading']);
Route::get('manifest', [ApiController::class, 'manifest']);











Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//route for stock categories
Route::get('/stock-categories', function (Request $request) {
    $q = $request->get('q');
    $company_id = $request->get('company_id');
    if($company_id==null){
        return response()->json(['error' => 'Company ID is required'], 400);
    }

    $sub_categories = StockSubcategory::where('company_id', $company_id)
    ->where('name', 'like', "%{$q}%")
        ->orderBy('name', 'asc')
        ->limit(20)
    ->get();
    $data = [];

    foreach ($sub_categories as $subcategory) {
        $data[] = [
            'id' => $subcategory->id,
            'text' => $subcategory->name. "(" . $subcategory->measuring_unit . ")",

        ];
    }
    return response()->json([
        'data' => $data,
    ]);

   
});
