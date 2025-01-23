<?php

namespace App\Admin\Controllers;

use App\Models\BloodDonationEvent;
use App\Models\BloodBankAdmin;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Form;

class BloodDonationEventController extends AdminController
{
    protected $title = 'Blood Donation Events';

    // Grid for listing events
    protected function grid()
    {
        $grid = new Grid(new BloodDonationEvent());

        // Define the grid columns
        $grid->column('eventID', __('ID'))->sortable();
        $grid->column('eventName', __('Event Name'))->sortable();
        $grid->column('eventLocation', __('Event Location'));
        $grid->column('eventDate', __('Event Date'))->sortable();
        
        // Display the related BloodBankAdmin name
        $grid->column('bloodBankAdmin.name', __('Uploaded By'))->sortable();

        // Apply filters
        $grid->filter(function ($filter) {
            $filter->like('eventName', 'Event Name');
            $filter->like('eventDate', 'Event Date')->date();
        });

        return $grid;
    }

    // Show the details of a single event
    protected function detail($id)
    {
        $show = new Show(BloodDonationEvent::findOrFail($id));

        // Define fields to display in the detail view
        $show->field('eventID', __('ID'));
        $show->field('eventName', __('Event Name'));
        $show->field('eventLocation', __('Event Location'));
        $show->field('eventDate', __('Event Date'));
        
        // Display the related BloodBankAdmin name in detail view
        $show->field('bloodBankAdmin.name', __('Uploaded By'));


        return $show;
    }

    protected function form()
    {
    $form = new Form(new BloodDonationEvent());

    // Event Name field
    $form->text('eventName', __('Event Name'))->rules('required');

    // Event Location field
    $form->text('eventLocation', __('Event Location'))->rules('required');

    // Event Date field
    $form->datetime('eventDate', __('Event Date'))->rules('required|date');

    // Select Blood Bank Admin for the event
    // This dropdown will list all the Blood Bank Admins
    $form->select('blood_bank_admin_id', __('Organized By'))
        ->options(BloodBankAdmin::all()->pluck('name', 'id'))
        ->rules('required');

    return $form;
    }
}
