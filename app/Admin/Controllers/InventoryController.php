<?php

namespace App\Admin\Controllers;

use App\Models\Inventory;
use App\Models\BloodBankAdmin;
use Carbon\Carbon;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class InventoryController extends AdminController
{
    protected $title = 'Blood Inventory';

    protected function grid()
    {
        $grid = new Grid(new Inventory());

        // Add a dropdown to select a blood bank
        $grid->header(function () {
            $bloodBanks = BloodBankAdmin::all(); // Fetch all blood banks
            $bloodBankOptions = '';

            foreach ($bloodBanks as $bloodBank) {
                $selected = request('blood_bank_id') == $bloodBank->id ? 'selected' : '';
                $bloodBankOptions .= "<option value='{$bloodBank->id}' {$selected}>{$bloodBank->name}</option>";
            }

            return <<<HTML
                <form method="GET" id="filter-form" style="margin-bottom: 15px;">
                    <label for="blood-bank-select" style="font-weight: bold; margin-right: 10px;">Select Blood Bank:</label>
                    <select id="blood-bank-select" name="blood_bank_id" onchange="document.getElementById('filter-form').submit()">
                        <option value="">-- Select Blood Bank --</option>
                        {$bloodBankOptions}
                    </select>
                    <style>
                        #blood-bank-select {
                            padding: 5px;
                            border-radius: 5px;
                            border: 1px solid #ddd;
                        }
                    </style>
                </form>
            HTML;
        });

        // Filter the grid based on the selected blood bank
        $grid->model()->when(request('blood_bank_id'), function ($query) {
            $query->where('blood_bank_id', request('blood_bank_id'));
        });

        // Disable the export button if no blood bank is selected
        if (!request('blood_bank_id')) {
            $grid->disableExport();
        }

        // Customize the columns
        $grid->column('blood_type', 'Blood Type')->display(function () {
            return "<strong>{$this->blood_type}</strong>";
        });

        $grid->column('quantity', 'Quantity')->display(function () {
            return "<span style='color: blue;'>{$this->quantity} Units</span>";
        });

        $grid->column('dateUpdated', 'Last Updated')->display(function () {
            return Carbon::parse($this->dateUpdated)->format('d-m-Y H:i');
        });

        return $grid;
    }



    // Configure the form for creating/editing inventory items
    protected function form()
    {
        $form = new Form(new Inventory());

        // Select for blood banks
        $form->select('blood_bank_id', 'Blood Bank')
            ->options(BloodBankAdmin::all()->pluck('name', 'id')) // Fetch all blood banks
            ->required();

        // Select for blood types
        $form->select('bloo_type', 'Blood Type')
            ->options(['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-'])
            ->required();

        // Quantity input with validation
        $form->number('quantity', 'Quantity')->min(0)->required();

        // Date updated field with default set to current date
        $form->date('dateUpdated', 'Date Updated')->default(Carbon::now());

        return $form;
    }

    // Optionally, configure how individual inventory items are shown
    protected function detail($id)
    {
        $show = new Show(Inventory::findOrFail($id));

        $show->field('inventoryID', 'Inventory ID');
        $show->field('blood_type', 'Blood Type');
        $show->field('quantity', 'Quantity');
        $show->field('blood_bank_id', 'Blood Bank')->as(function ($bloodBankId) {
            $bloodBank = BloodBankAdmin::find($bloodBankId);
            return $bloodBank ? $bloodBank->name : 'Unknown';
        });
        $show->field('dateUpdated', 'Last Updated Date');

        return $show;
    }
}
