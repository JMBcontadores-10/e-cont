<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class Tablacheques extends DataTableComponent

{


    public function columns(): array
    {
        return [
            Column::make('fecha')
            ->sortable(),
           
            Column::make('importecheque')
            ->sortable()
            ->searchable(),
          
          
            Column::make('tipo operacion', 'tipoopera')
                ->sortable(),
                
                Column::make('Beneficiario')
                ->sortable(),
        ];
    }

    public function query(): Builder
    {
        return Cheques::query();
    }



    
 
}
