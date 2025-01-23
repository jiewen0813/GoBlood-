<?php

namespace App\Admin\Controllers;

use App\Models\Reward;
use App\Models\Redemption;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use App\Admin\Actions\ViewRedemptionsAction;

class RewardController extends AdminController
{
    /**
     * Create a grid for displaying the rewards.
     */
    protected function grid()
    {
        $grid = new Grid(new Reward());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('reward_name', __('Name'))->sortable();
        $grid->column('points_required', __('Points Required'))->sortable();
        $grid->column('voucher_limit', __('Voucher Limit'))->sortable();
        $grid->column('remaining_vouchers', __('Remaining Vouchers'))->sortable();
        $grid->column('description', __('Description'));
        $grid->column('created_at', __('Created At'))->sortable();
        $grid->column('updated_at', __('Updated At'))->sortable();

        $grid->actions(function ($actions) {
            $actions->disableView(); // Disable the "View" button if unnecessary

            // Add the custom "View Redemptions" action
            $actions->add(new ViewRedemptionsAction());
        });

        return $grid;
    }

    /**
     * Create a form for creating/editing rewards.
     */
    protected function form()
    {
        $form = new Form(new Reward());

        $form->text('reward_name', __('Reward Name'))->required();
        $form->number('points_required', __('Points Required'))->required()->min(1);
        $form->number('voucher_limit', __('Voucher Limit'))
            ->required()
            ->help('Set the maximum number of vouchers available for this reward.')
            ->default(0);
        $form->hidden('remaining_vouchers')->default(0);

        $form->saving(function ($form) {
            $form->remaining_vouchers = $form->voucher_limit;
        });
        $form->textarea('description', __('Description'));

        return $form;
    }

    /**
     * Show redemptions for a specific reward.
     */
    public function redemptions($rewardId)
    {
        $redemptions = Redemption::where('reward_id', $rewardId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.rewards.redemptions', compact('redemptions'));
    }
}
