<?php

namespace App\Admin\Controllers;

use App\Models\FinancialPeriod;
use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\StockSubcategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Utils;

class StockItemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock Item';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StockItem());
        $grid->filter(function ($filter) {
            // Add a quick search filter
            $filter->like('name', __('Name'));
            $filter->like('sku', __('Sku'));
            $u = Admin::user();
            $filter->equal('stock_subcategory_id', __('Stock subcategory id'))
            ->select(StockSubcategory::where('company_id', $u->company_id)
                ->pluck('name', 'id')
                ->map(function ($name, $id) {
                    return $name . " (" . StockSubcategory::find($id)->measuring_unit . ")";
                }));
        });



        $grid->disableBatchActions();

        $u = Admin::user();
        $grid->model()->where('company_id', $u->company_id);    

        $grid->column('id', __('Id'))->sortable();
        $grid->column('stock_category_id', __('Stock category id'))->display(function ($stockCategoryId) {
            $category = StockCategory::find($stockCategoryId);
            return $category ? $category->category_name : 'Unknown';
        })->sortable()->hide();
       

        $grid->column('stock_subcategory_id', __('Stock subcategory id'))->display(function ($stockSubcategoryId) {
            $subcategory = StockSubcategory::find($stockSubcategoryId);
            return $subcategory ? $subcategory->name . " (" . $subcategory->measuring_unit . ")" : 'Unknown';
        })->sortable();
        $grid->column('financial_period_id', __('Financial period id'))
        ->display(function ($financialPeriodId) {
            $financialPeriod = FinancialPeriod::find($financialPeriodId);
            return $financialPeriod ? $financialPeriod->name : 'Unknown';
        })->sortable()->hide();
        $grid->column('name', __('Name'));
        $grid->column('description', __('Description'))->hide();
        
        $grid->column('barcode', __('Barcode'))->sortable();
        $grid->column('sku', __('Sku'));
        $grid->column('buying_price', __('Buying price'))->sortable()->display(function ($buyingPrice) {
            return number_format($buyingPrice, 2);
        });
        $grid->column('selling_price', __('Selling price'));
        $grid->column('original_qty', __('Original qty'));
        $grid->column('current_qty', __('Current qty'));
                $grid->column('created_by', __('Created by'))->display(function ($createdBy) {
            $user = \App\Models\User::find($createdBy);
            return $user ? $user->name : 'Unknown';
        });

                $grid->column('created_at', __('Created at'))->display(function ($createdAt) {
            return date('Y-m-d', strtotime($createdAt));
        })->sortable();


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(StockItem::findOrFail($id));

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
        $show->field('image', __('Image'));
        $show->field('barcode', __('Barcode'));
        $show->field('sku', __('Sku'));
        $show->field('genarate_sku', __('Genarate sku'));
        
        $show->field('buying_price', __('Buying price'));
        $show->field('selling_price', __('Selling price'));
        $show->field('original_qty', __('Original qty'));
        $show->field('current_qty', __('Current qty'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
   protected function form()
{   
    $u = Admin::user();

    $financial_period = Utils::getActiveFinancialPeriod($u->company_id);

    if ($financial_period == null) {
        return admin_error('No active financial period found', 'Please create an active financial period first.');
    }

    $form = new Form(new StockItem());

    if (!$u->company_id) {
        return admin_error('Invalid company', 'Company ID missing.');
    }

    $form->hidden('company_id', __('Company id'))->default($u->company_id);
    $form->hidden('created_by', __('created by id'))->default($u->id);

    // Correct URL for ajax loading stock categories
    $stock_category_url = url('/api/stock-categories') . '?company_id=' . $u->company_id;

    $form->select('stock_subcategory_id', __('Stock  subcategory'))
        ->ajax($stock_category_url)
        ->options(function ($id) {
            $category = \App\Models\StockCategory::find($id);
            if ($category) {
                return [$category->id => $category->category_name];
            } else {
                return [];
            }
        })
        ->required();

    $form->text('name', __('Name'))->required();
    $form->image('image', __('Image'))->uniqueName();
    $form->text('sku', __('Sku'));

    // Always show generate_sku radio
    // Show update_sku radio only when editing
if ($form->isEditing()) {
    $form->radio('update_sku', __('Update SKU'))
        ->options([
            'Yes' => 'Yes',
            'No' => 'No',
        ])
        ->when('Yes', function (Form $form) {
            $form->text('sku', __('Enter SKU (Batch Number)'))->required();
        })
        ->default('No');

    // Prevent saving to DB (column doesn't exist)
    $form->ignore(['update_sku']);
} else {
    $form->radio('genarate_sku', __('Generate SKU'))
        ->options([
            'Manual' => 'Manual',
            'Auto' => 'Auto',
        ])
        ->when('Manual', function (Form $form) {
            $form->text('sku', __('Enter SKU (Batch Number)'))->required();
        })
        ->required()
        ->default('Auto');
}

$form->multipleImage('gallery', __('Item Gallery'))
    ->removable()
    ->uniqueName()
    ->help('You can upload multiple images for the item.')
    ->downloadable();

$form->decimal('buying_price', __('Buying price'))->default(0)->required();
$form->decimal('selling_price', __('Selling price'))->default(0.00)->required();
$form->decimal('original_qty', __('Original qty'))->default(0.00)->required();

$form->textarea('description', __('Description'));

return $form;
}
}