<?php

namespace App\Admin\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\BloodBankAdmin;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class AppointmentController extends AdminController
{
    protected $title = 'Appointments';

    // Grid for listing appointments for all users across all blood banks
    protected function grid()
    {
        $grid = new Grid(new Appointment());

        // Define the grid columns to view all appointments
        $grid->column('id', __('Appointment ID'))->sortable();
        $grid->column('user.name', __('User Name'))->sortable();
        $grid->column('bloodBankAdmin.name', __('Blood Bank'))->sortable();
        $grid->column('appointment_date', __('Appointment Date'))->sortable();
        $grid->column('time_slot', __('Time Slot'))->sortable();
        $grid->column('status', __('Status'))->sortable();

        // Apply filters
        $grid->filter(function ($filter) {
            $filter->like('user.name', 'User Name');
            $filter->like('bloodBankAdmin.name', 'Blood Bank');
            $filter->between('appointment_date', 'Appointment Date')->date();
            $filter->equal('status', 'Status')->select([
                'Pending' => 'Pending',
                'Approved' => 'Approved',
                'Cancelled' => 'Cancelled',
                'Completed' => 'Completed',
            ]);
        });

        $grid->actions(function ($actions) {
            // Remove the Edit action for super admin
            $actions->disableEdit();
        });

        return $grid;
    }

    // Show the details of a single appointment
    protected function detail($id)
    {
        $show = new Show(Appointment::findOrFail($id));

        $show->field('id', __('Appointment ID'));
        $show->field('user.name', __('User Name'));
        $show->field('bloodBankAdmin.name', __('Blood Bank'));
        $show->field('appointment_date', __('Appointment Date'));
        $show->field('time_slot', __('Time Slot'));
        $show->field('status', __('Status'));

        return $show;
    }
}
