<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;

class PublicOrganisasiController extends Controller
{
    public function show($nama)
    {
        $organisasi = Organisasi::where('nama', $nama)->firstOrFail();

        return view('pages.details.organisasi-detail', compact('organisasi'));
    }
}