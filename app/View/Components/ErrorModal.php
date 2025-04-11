<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ErrorModal extends Component
{
    public $errors;
    public $modalId;
    public $title;

    /**
     * Create a new component instance.
     */
    public function __construct($modalId = 'errorModal', $title = 'Erro no Formulário')
    {
        $this->errors = session()->get('errors') ?: collect();
        $this->modalId = $modalId;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.error-modal');
    }
}
