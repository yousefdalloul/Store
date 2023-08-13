<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InvalidOrderException extends Exception
{

//    public function report()
//    {
//
//    }

    public function render(Request $request): \Illuminate\Http\RedirectResponse
    {
        return Redirect::route('home')
            ->withInput()
            ->withErrors([
            'message'=>$this->getMessage(),
        ])->with('info',$this->getMessage());
    }
}
