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
            Column::make('Fecha','fecha')
            ->sortable()
            ->searchable(),
            Column::make('importe','importecheque')
            ->sortable()
            ->searchable(),
            Column::make('Nombre','numcheque')
                ->sortable()
                ->searchable(),
            Column::make('beneficiario', 'Beneficiario')
                ->sortable()
                ->searchable(),
            Column::make('tipo operacion', 'tipoopera')
                ->sortable(),
        ];
    }

    public function query(): Builder
    {
        return Cheques::query();
    }



    
 
}
