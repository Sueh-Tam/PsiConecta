<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SuccessModal extends Component
{
    public $modalId;
    public $title;
    public $message;
    public $show;

    public function __construct(
        $modalId = 'successModal',
        $title = 'Sucesso!',
        $message = null
    ) {
        $this->modalId = $modalId;
        $this->title = $title;
        $this->message = $message ?? session('success_message');
        $this->show = session('show_success_modal', false);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.success-modal');
    }
}
