<?php

namespace App\Admin\Controllers;

use App\Models\Donation;
use App\Models\User; // Import the User model
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class DonationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Donation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
{
    $grid = new Grid(new Donation());

    // Add columns to the grid
    $grid->column('id', __('Donation ID'))->sortable();
    $grid->column('blood_serial_no', __('Blood Serial No'))->sortable();
    $grid->column('date_donated', __('Date Donated'))->sortable();
    $grid->column('user_id', __('User ID'))->sortable(); // Display User ID

    // Display the user's name in the grid
    $grid->column('user_name', __('User Name'))->display(function () {
        return optional($this->user)->name ?? 'N/A'; // Fetch the user name dynamically
    })->sortable();

    // Add search functionality for IC Number
    $grid->filter(function ($filter) {
        $filter->like('user.ic_number', __('IC Number')); // Assuming the user relationship is defined
    });

    // Customize action buttons
    $grid->actions(function ($actions) {
        // Disable the view button if not needed
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
        $show = new Show(Donation::findOrFail($id));

        // Define fields to show donation details
        $show->field('id', __('Donation ID'));
        $show->field('blood_serial_no', __('Blood Serial No'));
        $show->field('date_donated', __('Date Donated'));
        $show->field('user_id', __('User ID')); // Display User ID

        // Display the user's name
        $show->field('user_name', __('User Name'))->as(function () {
            return User::find($this->user_id)->name ?? 'N/A'; // Fetch the user name dynamically
        });

        return $show;
    }

    /**
     * Make a form builder for creating and editing donations.
     *
     * @return Form
     */
    protected function form()
{
    $form = new Form(new Donation());

    // Add fields for the form
    $form->text('blood_serial_no', __('Blood Serial No'))->required();
    $form->date('date_donated', __('Date Donated'))->required();

    // Get all users and create an options array with name and IC number
    $options = User::all()->pluck('name', 'id')->map(function ($name, $id) {
        return $name . ' (' . User::find($id)->ic_number . ')'; // Display name with IC Number
    });

    // Dropdown for selecting user by name and IC Number
    $form->select('user_id', __('User (Name and IC Number)'))
        ->options($options)
        ->required(); // Make the selection required

    return $form;
}

}
