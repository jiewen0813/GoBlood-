<?php

namespace App\Admin\Controllers;

use App\Models\BloodBankAdmin;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class BloodBankAdminController extends AdminController
{
    protected $title = 'Blood Bank Admin';

    protected function grid()
    {
        $grid = new Grid(new BloodBankAdmin());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('username', __('Username'))->sortable();
        $grid->column('contact', __('Contact'))->sortable();
        $grid->column('address', __('Address'))->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            // Allow searching by 'name' with a 'like' filter
            $filter->like('name', 'Name');
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(BloodBankAdmin::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('Name'));
        $show->field('username', __('Username'));
        $show->field('contact', __('Contact'));
        $show->field('address', __('Address'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new BloodBankAdmin());

        $form->text('name', __('Name'))->required();
        $form->text('username', __('Username'))
            ->required()
            ->rules('required|unique:blood_bank_admins,username'); // Apply unique validation rule
        $form->password('password', __('Password'))
            ->required()
            ->rules('required|min:8'); // Apply min length validation
        $form->text('contact', __('Contact'))->required();
        $form->text('address', __('Address'))->required();
        $form->text('latitude', __('Latitude'))->rules('required');
        $form->text('longitude', __('Longitude'))->rules('required');


        return $form;
    }

    // Adjust the store method to match the parent method signature
    public function store()
    {
        $request = request();  // Use the request helper to get the request data
        $data = $request->all();
        $data['password'] = Hash::make($request->password); // Hash the password
        BloodBankAdmin::create($data); // Create the Blood Bank Admin

        return redirect()->route('blood-bank-admins.index')->with('success', 'Blood Bank Admin created successfully.');
    }

    public function update($id)
    {
        $request = request();  // Use the request helper to get the request data
        $bloodBankAdmin = BloodBankAdmin::findOrFail($id);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password); // Hash the new password if filled
        }

        $bloodBankAdmin->update($data); // Update the Blood Bank Admin

        return redirect()->route('blood-bank-admins.index')->with('success', 'Blood Bank Admin updated successfully.');
    }
}
