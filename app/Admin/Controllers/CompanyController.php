<?php

namespace App\Admin\Controllers;

use App\Models\Company;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Support\Facades\DB; // Import DB facade
use Encore\Admin\Auth\Database\Administrator; // Import Administrator model

class CompanyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Companies';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Company());
        $grid->disableBatchActions();
        $grid->quickSearch('name', 'email', 'website', 'phone1', 'phone2')
            ->placeholder('Search by name, email, website, phone1, or phone2');

        $grid->column('id', __('ID'))->hide();
        $grid->column('administrator.name', 'owner');
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('website', __('Website'));
        $grid->column('logo', __('Logo'));
        $grid->column('status', __('Status'))->display(function ($status) {
            return $status ? 'Active' : 'Inactive';
        })->sortable();
        $grid->column('address', __('Address'))->hide();
        $grid->column('phone1', __('Phone 1'));
        $grid->column('phone2', __('Phone 2'))->hide();
        $grid->column('pobox', __('P.O. Box'));
        $grid->column('colour', __('Colour'));
        $grid->column('slogan', __('Slogan'))->hide();
        $grid->column('expiry_license', __('Expiry License'));
        $grid->column('created_at', __('Created At'))
        ->display(function ($createdAt) {
            return date('Y-m-d ', strtotime($createdAt));
        })->sortable();
        $grid->column('updated_at', __('Updated At'));

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

        $show->field('id', __('ID'));
        $show->field('administrator.name', 'Administrator');
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('website', __('Website'));
        $show->field('logo', __('Logo'));
        $show->field('status', __('Status'));
        $show->field('address', __('Address'));
        $show->field('phone1', __('Phone 1'));
        $show->field('phone2', __('Phone 2'));
        $show->field('pobox', __('P.O. Box'));
        $show->field('colour', __('Colour'));
        $show->field('slogan', __('Slogan'));
        $show->field('expiry_license', __('Expiry License'));
        $show->field('created_at', __('Created At'));
        $show->field('updated_at', __('Updated At'));

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

        // Find role with ID 2
        $role = Role::find(2);

        $company_admins = [];

        if ($role) {
            // Get user IDs for this role from pivot table
            $userIds = DB::table('admin_role_users')
                ->where('role_id', 2)
                ->pluck('user_id');

            // Get users from Administrator model
            $users = Administrator::whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                $company_admins[$user->id] = $user->name;
            }
        }

        $form->select('Owner_id', 'Company Owner')->options($company_admins)->required();
        $form->text('name', __('Company name'))->required();
        $form->email('email', __('Email'));
        $form->url('website', __('Website'));
        $form->image('logo', __('Logo'));
        $form->text('status', __('Status'));
        $form->text('address', __('Address'));
        $form->text('phone1', __('Phone 1'));
        $form->text('phone2', __('Phone 2'));
        $form->text('pobox', __('P.O. Box'));
        $form->color('colour', __('Color'));
        $form->text('slogan', __('Slogan'));
        $form->date('expiry_license', __('Expiry License'))->default(date('Y-m-d'));

        return $form;
    }
}
