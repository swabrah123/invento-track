<?php

namespace App\Admin\Controllers;

use App\Models\StockCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StockCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'StockCategory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StockCategory());
        $u = Admin::user();
        $grid->model()->where('company_id', $u->company_id);
        $grid->disableBatchActions();
        $grid->quickSearch('category_name', 'description')
            ->placeholder('Search by category name or description');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('company_id', __('Company id'))->hide();
        $grid->column('category_name', __('Category name'));
        $grid->column('description', __('Description'));
        $grid->column('status', __('Status'));
        $grid->picture('Image', __('Image'));
        $grid->column('buying_price', __('Buying price'))->sortable()->display(function ($buyingPrice) {
            return number_format($buyingPrice, 2);
        });
        $grid->column('selling_price', __('Selling price'))->sortable()->display(function ($sellingPrice) {
            return number_format($sellingPrice, 2);
        });
        $grid->column('expected_profit', __('Expected profit'))->sortable()->display(function ($expectedProfit) {
            return number_format($expectedProfit, 2);
        });
        $grid->column('earned_profit', __('Earned profit'));
        $grid->column('created_at', __('Created at'))
            ->display(function ($createdAt) {
                return date('Y-m-d', strtotime($createdAt));
            })->sortable();
        $grid->column('updated_at', __('Updated at'))
            ->display(function ($updatedAt) {
                return date('Y-m-d', strtotime($updatedAt));
            });

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
        $show = new Show(StockCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('company_id', __('Company id'));
        $show->field('category_name', __('Category name'));
        $show->field('description', __('Description'));
        $show->field('status', __('Status'));
        $show->field('Image', __('Image'));
        $show->field('buying_price', __('Buying price'));
        $show->field('selling_price', __('Selling price'));
        $show->field('expected_profit', __('Expected profit'));
        $show->field('earned_profit', __('Earned profit'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new StockCategory());
        $u = Admin::user();

        $form->hidden('company_id')->default($u->company_id);
        $form->text('category_name', __('Category name'))->required();
        $form->radio('status', __('Status'))
        ->options(['active' => 'Active', 'inactive' => 'Inactive'])
        ->default('active');
        
        $form->image('Image', __('Image'));
                $form->textarea('description', __('Category Description'));

       

        return $form;
    }
}
