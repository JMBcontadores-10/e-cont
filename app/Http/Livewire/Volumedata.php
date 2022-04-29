<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Volumedata extends Component
{
    //Variables globales
    public $dia;
    public $empresa;

    //Variales para almacenar en la base de datos
    //Inventario inicial
    public $inventinicmagna;
    public $inventinicpremium;
    public $inventinicdiesel;

    //Compras
    public $compramagna;
    public $comprapremium;
    public $compradiesel;

    //Litros vendidos
    public $litvendmagna;
    public $litvendpremium;
    public $litvenddiesel;

    //Precio venta
    public $precventmagna;
    public $precventpremium;
    public $precventdiesel;

    //Inventario real
    public $autostickmagna;
    public $autostickpremium;
    public $autostickdiesel;

    //Inventario determinado
    public $invdetermagna;
    public $invdeterpremium;
    public $invdeterdiesel;

    public function render()
    {
        return view('livewire.volumedata');
    }
}
