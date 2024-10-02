<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectorController extends Controller
{
    public function collectorindex()
    {
        if (auth()->user()->cannot('isCollector', User::class)) {
            abort(404);
        }
        return view('collector.collect');
    }

}
