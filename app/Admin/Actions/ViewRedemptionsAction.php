<?php

namespace App\Admin\Actions;

use OpenAdmin\Admin\Actions\RowAction;

class ViewRedemptionsAction extends RowAction
{
    public $name = 'View Redemptions';

    /**
     * Handle the row action.
     *
     * @param mixed $model
     * @return void
     */
    public function handle($model)
    {
        // Handle the action if needed, or leave blank for a redirect-only action
        return $this->response()->success('Action executed successfully!')->refresh();
    }

    /**
     * Define the URL for the action.
     *
     * @return string
     */
    public function href()
    {
        // Define the URL to view redemptions for this reward
        return '/admin/rewards/' . $this->row->id . '/redemptions';
    }
}
