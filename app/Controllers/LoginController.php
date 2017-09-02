<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\Cookie;

class LoginController extends Controller
{
    /**
     * Show Login form
     *
     * @return string
     */
    public function index()
    {
        return view('login.index');
    }

    /**
     * Handle login
     *
     */
    public function create()
    {
        dd($_POST);
    }
}
