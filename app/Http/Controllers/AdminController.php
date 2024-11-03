<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin's profile form.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }
}
