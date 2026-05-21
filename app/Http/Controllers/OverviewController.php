<?php

namespace App\Http\Controllers;

class OverviewController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.overview');
    }
}