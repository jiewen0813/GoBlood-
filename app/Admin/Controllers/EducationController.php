<?php

namespace App\Admin\Controllers;

use App\Models\Education;
use App\Models\BloodBankAdmin;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Form;

class EducationController extends AdminController
{
    protected $title = 'Education Resources';

    // Grid for listing education resources
    protected function grid()
    {
        $grid = new Grid(new Education());

        // Define the grid columns
        $grid->column('id', __('ID'))->sortable();
        $grid->column('title', __('Title'))->sortable();
        $grid->column('content', __('Content'))->limit(50); // Limiting the content preview
        $grid->column('created_by', __('Created By'))->display(function ($created_by) {
            return BloodBankAdmin::find($created_by)->name;
        });

        // Apply filters
        $grid->filter(function ($filter) {
            $filter->like('title', 'Title');
            $filter->equal('created_by', 'Created By')
           ->select(BloodBankAdmin::all()->pluck('name', 'id'));
        });

        return $grid;
    }

    // Show the details of a single education resource
    protected function detail($id)
    {
        $show = new Show(Education::findOrFail($id));

        // Define fields to display in the detail view
        $show->field('id', __('ID'));
        $show->field('title', __('Title'));
        $show->field('content', __('Content'));
        $show->field('created_by', __('Created By'))->as(function ($created_by) {
            return BloodBankAdmin::find($created_by)->name;
        });

        return $show;
    }

    // Form to create/edit education resources
    protected function form()
    {
        $form = new Form(new Education());

        // Title field
        $form->text('title', __('Title'))->rules('required');

        // Content field
        $form->textarea('content', __('Content'))->rules('required');

        // Select Blood Bank Admin for the education resource
        $form->select('created_by', __('Created By'))
            ->options(BloodBankAdmin::all()->pluck('name', 'id'))
            ->rules('required');

        return $form;
    }
}
