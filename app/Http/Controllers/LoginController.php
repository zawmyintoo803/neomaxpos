<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('auth.login'); // adjust view path
    }

    // Show register page
    public function registerPage()
    {
        return view('auth.register'); // adjust view path
    }

    // Handle login form submission
    public function login(Request $request)
    {
        // login logic here
    }

    // Handle registration form submission
    public function register(Request $request)
    {
        // register logic here
    }
}
