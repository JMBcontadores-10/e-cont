<?php

namespace App\Http\Livewire;

use App\Models\Cheques;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Pdfcheque extends ModalComponent
{

    public $users, $name, $email, $user_id;




protected function rules(){

    return [
        'name' => ''
    ];
}


    public function ver($id){

        $users = Cheques::where('id',$id)->first();
        $this->user_id = $id;
       // $this->name = $user->nombrec;
       // $this->email = $user->email;
       $this->dispatchBrowserEvent('pdf', []);

    }



    public function render()
    {

        $this->users = Cheques::where('Id','2021December16H10M44S58AM')->first();
        return view('livewire.pdfcheque');
    }
}
