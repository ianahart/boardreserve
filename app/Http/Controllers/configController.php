<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class configController extends Controller
{
    public function clearRoute()
    {
        \Artisan::call('route:clear');
    }
}
