<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return inertia('Locations/Index', ['locations' => $locations]);
    }

    public function create()
    {
        return inertia('Locations/Create');
    }

    public function edit(Location $location)
    {
        return inertia('Locations/Edit', ['location' => $location]);
    }
}
