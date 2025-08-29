<?php

namespace App\Admin\Controllers;

use App\Models\FinancialPeriod;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FinancialPeriodController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Financial Periods';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FinancialPeriod());
        $u = Admin::user();
        $grid->model()->where('company_id', $u->company_id)
            ->orderBy('start_date', 'DESC');
        $grid->disableBatchActions();
        $grid->quickSearch('name', 'start_date', 'end_date', 'status', 'description')
            ->placeholder('Search by name, start date, end date, status, or description');

       
        $grid->column('name', __('Name'))
            ->sortable()
            ->filter('like');
        $grid->column('start_date', __('Start date'))
            ->display(function ($startDate) {
                return date('Y-m-d', strtotime($startDate));
            })
            ->sortable()
            ->filter('date');
        $grid->column('end_date', __('End date'))
            ->display(function ($endDate) {
                return date('Y-m-d', strtotime($endDate));
            })
            ->sortable()
            ->filter('date');
        $grid->column('status', __('Status'))
            ->label([
                'active' => 'success',
                'inactive' => 'danger',
            ])
            ->sortable()
            ->filter([
                'active' => 'Active',
                'inactive' => 'Inactive',
            ]);
        $grid->column('description', __('Description'))->hide();
        $grid->column('total_Investiment', __('Total Investiment'));
        $grid->column('total_sales', __('Total sales'));
        $grid->column('Total_profit', __('Total profit'));
        $grid->column('total_expenses', __('Total expenses'));

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
        $show = new Show(FinancialPeriod::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('company_id', __('Company id'));
        $show->field('name', __('Name'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('status', __('Status'));
        $show->field('description', __('Description'));
        $show->field('total_Investiment', __('Total Investiment'));
        $show->field('total_sales', __('Total sales'));
        $show->field('Total_profit', __('Total profit'));
        $show->field('total_expenses', __('Total expenses'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new FinancialPeriod());
        $u = Admin::user();

        $form->hidden('company_id', __('Company id'))
            ->default($u->company_id)
          ;
        $form->text('name', __('Name'))
            ->required();
        $form->date('start_date', __('Start date'))->default(date('Y-m-d'));
        $form->date('end_date', __('End date'))->default(date('Y-m-d'));
        $form->radio('status', __('Status'))->options([
            'active' => 'Active',
            'inactive' => 'Inactive',
        ])->default('active');
        $form->textarea('description', __('Description'));
      

        return $form;
    }
}
