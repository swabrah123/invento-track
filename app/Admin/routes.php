<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\CompanyController;
use App\Admin\Controllers\StockCategoryController;
use App\Admin\Controllers\StockSubcategoryController;
use App\Admin\Controllers\FinancialPeriodController;
use App\Admin\Controllers\EmployeesController;
use App\Admin\Controllers\StockItemController;
use App\Admin\Controllers\StockRecordController;
use App\Admin\Controllers\CompanyEditController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('companies', CompanyController::class);
     $router->resource('stock-categories', StockCategoryController::class);
         $router->resource('stock-subcategories', StockSubcategoryController::class); 
         $router->resource('financial-periods', FinancialPeriodController::class);
        $router->resource('employees', EmployeesController::class);
            $router->resource('stock-items', StockItemController::class);
                $router->resource('stock-items-records', StockRecordController::class);
                    $router->resource('companie-edit', CompanyEditController::class);


});
