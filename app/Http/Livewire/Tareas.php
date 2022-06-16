<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Tareas extends Component
{
    public function render()
    {
        return view('livewire.tareas')
            ->extends('layouts.livewire-layout')
            ->section('content');
    }
}
