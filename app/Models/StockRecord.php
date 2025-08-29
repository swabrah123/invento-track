<?php

namespace App\Models;

use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRecord extends Model
{
    use HasFactory;
    //boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $stock_item = StockItem::find($model->stock_item_id);
            if ($stock_item == null) {
                throw new \Exception('Invalid stock item');
            }     
            $model->company_id = $stock_item->company_id;  
            $model->stock_category_id = $stock_item->stock_category_id;
            $model->stock_subcategory_id = $stock_item->stock_subcategory_id;
            if ($model->description == null) {
                $model->description = $stock_item->type;
            }
            $qty = abs($model->qty);
            if ($qty < 1) {
                throw new \Exception('Quantity must be greater than 0');
                }

            $current_quantity = $stock_item->current_quantity;
            if ($current_quantity < $qty && $model->type == 'out') {
                throw new \Exception('Insufficient stock quantity');
            }

            $new_quantity = $current_quantity- $qty;
            $stock_item->current_quantity = $new_quantity;
            $stock_item->save();

            //profit calculation
            $model ->profit = $model->total_sales - ($stock_item->buying_price * $qty);
            $model ->profit = abs($model ->profit);

            if ($model->type == 'sale') {
                $model->profit = abs($model->profit);
                } else
                $model->profit = -abs($model->profit);

            

            // Set created_by from logged-in admin user
            $adminUser = Admin::user();
            if ($adminUser) {
                $model->created_by = $adminUser->id;
            } else {
                throw new \Exception('No logged-in admin user found');
            }

            return $model;
        });
       
    }
}
