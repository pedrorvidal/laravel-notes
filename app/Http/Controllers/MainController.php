<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    //
    public function index($value) {
        return view('main', array('value' => $value));
    }
}
