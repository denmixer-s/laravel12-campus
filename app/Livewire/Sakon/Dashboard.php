<?php
namespace App\Livewire\Sakon;

use Livewire\Attributes\Layout;
use Livewire\Component;



// #[Layout('layouts.dashboard')]
class Dashboard extends Component
{

    #[Layout('components.layouts.dashboard')]

    public function render()
    {
        return view('livewire.sakon.dashboard');
    }
}
