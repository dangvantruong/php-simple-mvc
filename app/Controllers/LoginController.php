<?php

namespace App\Controllers;


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
