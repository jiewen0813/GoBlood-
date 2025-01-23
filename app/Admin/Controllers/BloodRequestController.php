<?php

namespace App\Admin\Controllers;

use App\Models\BloodRequest;
use App\Models\User;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class BloodRequestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Blood Requests';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BloodRequest());

        // Add columns to the grid
        $grid->column('id', __('ID'))->sortable();
        $grid->column('user.name', __('Requested By'))->sortable();
        $grid->column('request_type', __('Request Type'))->sortable();
        $grid->column('blood_type', __('Blood Type'))->sortable();
        $grid->column('quantity', __('Quantity'))->sortable();
        $grid->column('location', __('Location'))->sortable();
        $grid->column('phone', __('Contact Number'))->sortable();
        $grid->column('status', __('Status'))->sortable();
        $grid->column('created_at', __('Requested At'))->sortable();

        $grid->filter(function ($filter) {
            // Add filters for searching
            $filter->like('user.name', 'Requested By');
            $filter->equal('blood_type', 'Blood Type')->select([
                'A+' => 'A+',
                'B+' => 'B+',
                'O+' => 'O+',
                'AB+' => 'AB+',
                'A-' => 'A-',
                'B-' => 'B-',
                'O-' => 'O-',
                'AB-' => 'AB-',
            ]);
            $filter->equal('status', 'Status')->select([
                'Pending' => 'Pending',
                'Completed' => 'Completed',
            ]);
        });

        // Customize action buttons
        $grid->actions(function ($actions) {
            // Disable the view button if not needed
            $actions->disableView();
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
        $show = new Show(BloodRequest::findOrFail($id));

        // Define fields to show blood request details
        $show->field('id', __('ID'));
        $show->field('user.name', __('Requested By'));
        $show->field('request_type', __('Request Type'));
        $show->field('blood_type', __('Blood Type'));
        $show->field('quantity', __('Quantity'));
        $show->field('location', __('Location'));
        $show->field('phone', __('Contact Number'));
        $show->field('notes', __('Notes'));
        $show->field('status', __('Status'));
        $show->field('ic_number', __('IC Number'));
        $show->field('created_at', __('Requested At'));
        $show->field('updated_at', __('Last Updated'));

        return $show;
    }

    /**
     * Make a form builder for creating and editing blood requests.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BloodRequest());

        // Add fields for the form
        $form->select('user_id', __('Requested By'))->options(function () {
            return User::all()->pluck('name', 'id');
        })->required();
        $form->select('request_type', __('Request Type'))->options([
            'Self' => 'Self',
            'Other' => 'Other',
        ])->required();
        $form->select('blood_type', __('Blood Type'))->options([
            'A+' => 'A+',
            'B+' => 'B+',
            'O+' => 'O+',
            'AB+' => 'AB+',
            'A-' => 'A-',
            'B-' => 'B-',
            'O-' => 'O-',
            'AB-' => 'AB-',
        ])->required();
        $form->number('quantity', __('Quantity'))->required();
        $form->text('location', __('Location'))->required();
        $form->text('phone', __('Contact Number'))->required();
        $form->textarea('notes', __('Notes'));
        $form->select('status', __('Status'))->options([
            'Pending' => 'Pending',
            'Completed' => 'Completed',
        ])->required();
        $form->text('ic_number', __('IC Number'))->required();

        return $form;
    }
}