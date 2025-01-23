<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HealthDetailModal extends Component
{
    /**
     * The health detail data passed to the component.
     *
     * @var array
     */
    public $detail;

    /**
     * Create a new component instance.
     *
     * @param array $detail
     */
    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.health-detail-modal');
    }
}
