<?php

namespace App\Admin\Controllers;

use App\Models\StockSubcategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\StockCategory;

class StockSubcategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock Subcategories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
{
    $grid = new Grid(new StockSubcategory());
    $u = Admin::user();

    $grid->model()->with('stockCategory')
        ->where('company_id', $u->company_id)
        ->orderBy('name', 'ASC');

    $grid->column('id', __('Id'))->sortable();
    $grid->column('company_id', __('Company id'))->hide();

$grid->column('stockCategory.category_name', __('Category Name'));

    $grid->column('name', __('Name'))->sortable();
    $grid->column('description', __('Description'))->hide();

    $grid->column('status', __('Status'));

    $grid->column('Image', __('Image'))->lightbox([
    'width'     => 50,
    'height'    => 50,
    'zoomable'  => true,
    'removable' => false,
    'deletable' => false,
]);


    $grid->column('buying_price', __('Buying price'))->sortable()->display(function ($value) {
        return number_format($value, 2);
    });
    $grid->column('selling_price', __('Selling price'))->sortable()->display(function ($value) {
        return number_format($value, 2);
    });
    $grid->column('expected_profit', __('Expected profit'))->sortable()->display(function ($value) {
        return number_format($value, 2);
    });
    $grid->column('earned_profit', __('Earned profit'))->sortable()->display(function ($value) {
        return number_format($value, 2);
    });

    $grid->column('measuring_unit', __('Measuring unit'))->hide();

    $grid->column('current_quantity', __('Current quantity'))->display(function ($value) {
        return number_format($value, 2) . ' ' . ($this->measuring_unit ?? '');
    })->sortable();

    $grid->column('reorder_level', __('Reorder level'))
        ->display(function ($value) {
            return number_format($value, 2);
        })
        ->sortable()
        ->editable();

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
        $show = new Show(StockSubcategory::findOrFail($id));
        

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('company_id', __('Company id'));
        $show->field('stock_category_id', __('Stock category id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('status', __('Status'));
$show->field('Image')->as(function ($path) {
    return "<img src='" . asset('storage/' . $path) . "' style='max-width:100px; height:auto;' />";
})->unescape();

        $show->field('buying_price', __('Buying price'));
        $show->field('selling_price', __('Selling price'));
        $show->field('expected_profit', __('Expected profit'));
        $show->field('earned_profit', __('Earned profit'));
        $show->field('measuring_unit', __('Measuring unit'));
        $show->field('current_quantity', __('Current quantity'));
        $show->field('reorder_level', __('Reorder level'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
{
    $form = new Form(new StockSubcategory());

    $u = Admin::user();
    $form->hidden('company_id')->default($u->company_id);

    // Use StockCategory for dropdown
    $categories = StockCategory::where([
        'company_id' => $u->company_id,
        'status' => 'active'
    ])->pluck('category_name', 'id');

    $form->select('stock_category_id', __('Stock Category'))
        ->options($categories)
        ->rules('required')
        ->help('Select a stock category');

    $form->text('name', __('Name'))->rules('required');
    $form->textarea('description', __('Description'));
    
    $form->radio('status', __('Status'))->options([
        'active' => 'Active',
        'inactive' => 'Inactive',
    ])->default('active');

    $form->image('Image', __('Image'))->uniqueName();

    $form->text('measuring_unit', __('Measuring Unit'))->default('pcs')->required();
    $form->decimal('reorder_level', __('Reorder Level'))->default(0)->required();

    return $form;
}

}
