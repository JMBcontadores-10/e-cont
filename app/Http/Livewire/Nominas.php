<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Nominas extends Component
{
    public function render()
    {
        return view('livewire.nominas')
        ->extends('layouts.livewire-layout')
        ->section('content');
    }
}
