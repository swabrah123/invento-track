<?php

namespace App\Admin\Controllers;

use App\Models\StockRecord;      // Change here
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StockRecordController extends AdminController
{
    protected $title = 'Stock Out Record';

    protected function grid()
    {
        $grid = new Grid(new StockRecord());  
        $u = Admin::user();
        $grid->model()->where('company_id', $u->company_id);
        $grid->disableBatchActions();
        
      
     
          //stock_item_id nome instead of id
        $grid->column('stock_item_id', __('Stock Item'))->display(function ($stockItemId) {
            $stockItem = \App\Models\StockItem::find($stockItemId);
            return $stockItem ? $stockItem->name : 'Unknown';
        });

        $grid->column('stock_category_id', __('Stock category id'))->display(function ($stockCategoryId) {
            $category = \App\Models\StockCategory::find($stockCategoryId);
            return $category ? $category->category_name : 'Unknown';
        })->sortable()->hide();
        $grid->column('stock_subcategory_id', __('Stock subcategory '))->display(function ($stockSubcategoryId) {
            $subcategory = \App\Models\StockSubcategory::find($stockSubcategoryId);
            return $subcategory ? $subcategory->name . " (" . $subcategory->measuring_unit . ")" : 'Unknown';
        })->sortable();
        $grid->column('financial_period_id', __('Financial period id'))
            ->display(function ($financialPeriodId) {
                $financialPeriod = \App\Models\FinancialPeriod::find($financialPeriodId);
                return $financialPeriod ? $financialPeriod->name : 'Unknown';
            })->sortable()->hide();
        $grid->column('description', __('Description'))->hide();
        $grid->column('qty', __('Qty'))->display(function ($qty) {
            return $qty . ' units';
        })->totalRow(function ($qty) {
            return 'Total: ' . $qty . ' units';
        })->sortable();
        $grid->column('created_at', __('Created at'));


        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(StockRecord::findOrFail($id));  // Change here

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('company_id', __('Company id'));
        $show->field('created_by', __('Created by'));
        $show->field('stock_category_id', __('Stock category id'));
        $show->field('stock_subcategory_id', __('Stock subcategory id'));
        $show->field('financial_period_id', __('Financial period id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('qty', __('Qty'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new StockRecord());  // Change here

        $u = Admin::user();
        


        $form->hidden('company_id')->default($u->company_id);

       $form->select('stock_item_id', __('Stock Item'))
    ->options(\App\Models\StockItem::where('company_id', $u->company_id)->pluck('name', 'id'))
    ->required();


        $form->radio('type', __('Type'))
            ->options([
                'sale' => 'Sale',
                'damage' => 'Damage',
                'expired' => 'Expired',
                'lost' => 'Lost',
                'return' => 'Return',
                'other' => 'Other',
            ])
            ->required();

        $form->textarea('description', __('Description'));
        $form->decimal('qty', __('Qty'))->default(0);

        return $form;
    }
}
