<?php

namespace App\Admin\Controllers;

use App\Models\Redemption;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class RedemptionController extends AdminController
{
    /**
     * Title for current resource.
     */
    protected $title = 'Redemptions';

    /**
     * Make a grid builder.
     */
    protected function grid()
    {
        $grid = new Grid(new Redemption());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('user.name', __('User Name'));
        $grid->column('reward.name', __('Reward Name'));
        $grid->column('points_used', __('Points Used'));
        $grid->column('is_used', __('Status'))->bool([
            0 => 'Unused',
            1 => 'Used',
        ]);
        $grid->column('created_at', __('Redeemed At'))->sortable();
        $grid->column('used_at', __('Used At'))->sortable();

        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });

        // Add filters
        $grid->filter(function ($filter) {
            $filter->like('user.name', 'User Name');
            $filter->like('reward.name', 'Reward Name');
            $filter->equal('is_used', 'Status')->select([
                0 => 'Unused',
                1 => 'Used',
            ]);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     */
    protected function detail($id)
    {
        $show = new Show(Redemption::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('user.name', __('User Name'));
        $show->field('reward.name', __('Reward Name'));
        $show->field('points_used', __('Points Used'));
        $show->field('is_used', __('Status'));
        $show->field('created_at', __('Redeemed At'));
        $show->field('used_at', __('Used At'));

        return $show;
    }

    /**
     * Make a form builder.
     */
    protected function form()
    {
        $form = new Form(new Redemption());

        $form->display('id', __('ID'));
        $form->display('user.name', __('User Name'));
        $form->display('reward.name', __('Reward Name'));
        $form->display('points_used', __('Points Used'));
        $form->switch('is_used', __('Status'));
        $form->datetime('used_at', __('Used At'));

        return $form;
    }
}
