<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EmployeesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Workers';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $u = Admin::user();
        $grid->model()->where('company_id', $u->company_id);

        $grid->quickSearch('username', 'name', 'firstname', 'lastname')
            ->placeholder('Search by username, name, first name, or last name');
        
         $grid->disableBatchActions();
        $grid->column('avatar', __('Image'))->lightbox([
            'width' => 50,
            'height' => 50,
        ]);

        $grid->column('name', __('Name'))->sortable();

       
       
        $grid->column('firstname', __('Firstname'));
        $grid->column('lastname', __('Lastname'));
        $grid->column('phone1', __('Phone1'));
        $grid->column('sex', __('Gender'))->filter(['male' => 'male',
                                                    'female' => 'female']
            )->sortable();
        $grid->column('dob', __('Dob'))->sortable()->display(function ($dob) {
            return date('Y-m-d', strtotime($dob));
        });
        $grid->column('status', __('Status'))
            ->label([
                'active' => 'success',
                'inactive' => 'danger',
            ]);
         $grid->column('created_at', __('Registered at'))
            ->display(function ($createdAt) {
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('username', __('Username'));
        $show->field('password', __('Password'));
        $show->field('name', __('Name'));
        $show->field('avatar', __('Avatar'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('company_id', __('Company id'));
        $show->field('firstname', __('Firstname'));
        $show->field('lastname', __('Lastname'));
        $show->field('phone1', __('Phone1'));
        $show->field('phone2', __('Phone2'));
        $show->field('sex', __('Sex'));
        $show->field('dob', __('Dob'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
  protected function form()
{
    $form = new Form(new User());
    $u = Admin::user();

    $form->hidden('company_id')->default($u->company_id);
    $form->text('name')->required();
    $form->text('firstname')->required();
    $form->text('lastname')->required();
    $form->text('phone1')->required();
    $form->text('phone2');

    $form->divider('User Credentials');

    $form->image('avatar');

    $form->radio('sex', __('Gender'))->options([
    'male'   => 'male',
    'female' => 'female',
]);

    $form->date('dob')->default(date('Y-m-d'));

    $form->divider('Account Information');
    $form->text('username')->required();

  
    $form->radio('status')
        ->options(['active' => 'Active', 'inactive' => 'Inactive'])
        ->default('active');

    $form->saving(function (Form $form) {
        // Remove password_confirmation to prevent DB error
        unset($form->password_confirmation);

        // Handle password update only if provided
        if ($form->password) {
            $form->password = bcrypt($form->password);
        } else {
            // Keep old password if password field is empty
            $form->password = $form->model()->password;
        }
    });

    return $form;
}


}
