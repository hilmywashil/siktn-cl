<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Katalog;

class OutlineController extends Controller
{
    public function index()
    {
        $fullmembers = Katalog::all()->count();

        return view('pages.outline' , compact('fullmembers'));
    }
}
