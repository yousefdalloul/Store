<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    //Action
    public function index(){
        $title = 'Store';
        return view('dashboard.index',[
            'user' => 'Yousef Dalloul',
            'title' =>$title
        ]);

    }
}
