<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\StockRecord;
use App\Models\User;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $u = Admin::user();
        $company = Company::find($u->company_id);

        return $content
            ->row(function (Row $row) {
                // Total Employees
                $row->column(3, function (Column $column) {
                    $count = User::where('company_id', Admin::user()->company_id)->count();
                    $box = new Box('Employees', "<div style='padding:15px;text-align:right;font-size:18px;font-weight:bold;'>$count</div>");
                    $box->style('success')->solid();
                    $column->append($box);
                });

                // Total Sales
                $row->column(3, function (Column $column) {
                    $totalSales = StockRecord::where('company_id', Admin::user()->company_id)
                        ->sum('qty');
                    $box = new Box('Total Sales', "<div style='padding:15px;text-align:right;font-size:18px;font-weight:bold;'>$totalSales</div>");
                    $box->style('primary')->solid();
                    $column->append($box);
                });

                // Today's Sales
                $row->column(3, function (Column $column) {
                    $todaySales = StockRecord::where('company_id', Admin::user()->company_id)
                        ->whereDate('created_at', now()->toDateString())
                        ->sum('qty');
                    $box = new Box('Today\'s Sales', "<div style='padding:15px;text-align:right;font-size:18px;font-weight:bold;'>$todaySales</div>");
                    $box->style('info')->solid();
                    $column->append($box);
                });

                // This Week's Sales
                $row->column(3, function (Column $column) {
                    $weekSales = StockRecord::where('company_id', Admin::user()->company_id)
                        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->sum('qty');
                    $box = new Box('This Week Sales', "<div style='padding:15px;text-align:right;font-size:18px;font-weight:bold;'>$weekSales</div>");
                    $box->style('warning')->solid();
                    $column->append($box);
                });
            })
            ->row(function (Row $row) {
                // Financial Period Sales & Losses
               

                // Top Selling Categories
               
            });
    }
}
