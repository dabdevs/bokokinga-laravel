<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard/index');
    }

    public function login()
    {
        return view('dashboard/login');
    }
}
