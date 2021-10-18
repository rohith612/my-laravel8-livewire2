<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $form = [
        'email'   => '',
        'password'=> '',
    ];

    public function submit()
    {
        $this->validate([
            'form.email'    => 'required|email',
            'form.password' => 'required',
        ]);

        if(Auth::attempt($this->form)) return redirect(route('home'));
        else session()->flash('message', 'You have entered an invalid username or password');
    }


    public function render()
    {
        return view('livewire.login');
    }
}
