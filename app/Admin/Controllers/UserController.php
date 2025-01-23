<?php

namespace App\Admin\Controllers;

use App\Models\User;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Donor';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // Add columns to the grid
        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('email', __('Email'))->sortable();
        $grid->column('ic_number', __('IC Number'))->sortable();
        $grid->column('blood_type', __('Blood Type'))->sortable();
        $grid->column('dob', __('Date of Birth'))->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            // Add a like filter for the 'name' field
            $filter->like('name', 'Name');
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
        $show = new Show(User::findOrFail($id));

        // Define fields to show user details
        $show->field('id', __('ID'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('ic_number', __('IC Number'));
        $show->field('blood_type', __('Blood Type'));
        $show->field('phone', __('Phone Number'));
        $show->field('address', __('Address'));
        $show->field('dob', __('Date of Birth'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder for creating and editing users.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        // Add fields for the form
        $form->text('name', __('Name'))->required();
        $form->email('email', __('Email'))->required();
        $form->text('ic_number', __('IC Number'))->required();
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
        $form->text('phone', __('Phone Number'))->required();
        $form->text('address', __('Address'))->required();
        $form->date('dob', __('Date of Birth'))->required();

        return $form;
    }
}
