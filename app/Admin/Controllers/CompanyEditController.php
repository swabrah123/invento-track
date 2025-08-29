<?php

namespace App\Admin\Controllers;

use App\Models\Company;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CompanyEditController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Company';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Company());
        $grid->disableBatchActions();
        $grid->disableCreateButton();
        $u = \Encore\Admin\Facades\Admin::user();
        $grid->model()->where('Owner_id', $u->company_id);
        $grid->column('logo', __('Logo'))->image('',50,50);
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('website', __('Website'));
        $grid->column('status', __('Status'));
        $grid->column('phone1', __('Phone1'));
        $grid->actions(function ($actions) {
            $actions->disableDelete();
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
        $show = new Show(Company::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('Owner_id', __('Owner id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('website', __('Website'));
        $show->field('logo', __('Logo'));
        $show->field('status', __('Status'));
        $show->field('address', __('Address'));
        $show->field('phone1', __('Phone1'));
        $show->field('phone2', __('Phone2'));
        $show->field('pobox', __('Pobox'));
        $show->field('colour', __('Colour'));
        $show->field('slogan', __('Slogan'));
        $show->field('expiry_license', __('Expiry license'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('currency', __('Currency'));
        $show->field('setting_worker_can_create_stock_item', __('Setting worker can create stock item'));
        $show->field('setting_worker_can_create_stock_record', __('Setting worker can create stock record'));
        $show->field('setting_worker_can_create_stock_category', __('Setting worker can create stock category'));
        $show->field('setting_worker_can_view_stock_item', __('Setting worker can view stock item'));
        $show->field('setting_worker_can_view_stock_record', __('Setting worker can view stock record'));
        $show->field('setting_worker_can_view_stock_category', __('Setting worker can view stock category'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Company());

        $form->text('name', __('Name'))->required();
        $form->email('email', __('Email'));
        $form->text('website', __('Website'));
        $form->image('logo', __('Logo'));
        $form->text('address', __('Address'));
        $form->text('phone1', __('Phone1'));
        $form->text('phone2', __('Phone2'));
        $form->text('pobox', __('Pobox'));
        $form->text('colour', __('Colour'));
        $form->text('slogan', __('Slogan'));
        $form->divider('settings');
        $form->text('currency', __('Currency'))->default('USD')->required();
        $form->radio('setting_worker_can_create_stock_item', __('can  worker create stock item'))->options([1 => 'Yes', 0 => 'No'])->default(1);
        $form->radio('setting_worker_can_create_stock_record', __('can  worker create stock record'))->options([1 => 'Yes', 0 => 'No'])->default(1);
        $form->radio('setting_worker_can_create_stock_category', __('can  worker create stock category'))->options([1 => 'Yes', 0 => 'No'])->default(1);
        $form->radio('setting_worker_can_view_stock_item', __('can  worker view stock item'))->options([1 => 'Yes', 0 => 'No'])->default(1);
        $form->radio('setting_worker_can_view_stock_record', __('can  worker view stock record'))->options([1 => 'Yes', 0 => 'No'])->default(1);
        $form->radio('setting_worker_can_view_stock_category', __('can  worker view stock category'))->options([1 => 'Yes', 0 => 'No'])->default(1);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableDelete();
        });
        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableViewCheck();

        return $form;
    }
}
