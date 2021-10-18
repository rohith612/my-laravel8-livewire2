<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Register extends Component
{
    public $form = [
        'name'                  => '',
        'email'                 => '',
        'password'              => '',
        'password_confirmation' => '',
    ];

    public function submit(){
        $this->validate([
            'form.email'    => 'required|email',
            'form.name'     => 'required',
            'form.password' => 'required|confirmed',
        ]);
        try {
            User::create($this->form);
            return redirect(route('login'));
        } catch (\Throwable $th) {
            session()->flash('message', 'Cant"t register your account, please check the details');
        }
        
         
    }


    public function render()
    {
        return view('livewire.register');
    }
}
