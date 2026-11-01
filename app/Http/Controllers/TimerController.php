<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimerController extends Controller
{
    public function index(): View
    {
        return view('timers.index');
    }

    public function create(): View
    {
        return view('timers.create');
    }

    public function store(): RedirectResponse
    {

    }
}
