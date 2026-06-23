<?php

namespace App\Http\Controllers;

use App\Models\Feature;

class ArchiveController extends Controller
{
    public function index()
    {
        $features = Feature::where('status', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('ressources.archive', compact('features'));
    }
}