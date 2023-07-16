<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this ->middleware(['auth'])->only('index');
    }

    //Action
    public function index(){


        $title = 'Store';
        $user =Auth::user();
        return view('dashboard.index',[
            'user' => 'Yousef Dalloul',
            'title' =>$title
        ]);

    }
}
