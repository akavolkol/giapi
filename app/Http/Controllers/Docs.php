<?php

namespace App\Http\Controllers;

class Docs extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function api()
    {
        return view('docs.api');
    }
}