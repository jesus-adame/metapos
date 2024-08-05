<?php

namespace App\Http\Controllers\Central;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return inertia('Central/Settings/Index', ['settings' => $settings]);
    }

    public function create()
    {
        return inertia('Central/Settings/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable|string',
        ]);

        Setting::create($request->all());

        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    public function edit(Setting $setting)
    {
        return inertia('Central/Settings/Edit', ['setting' => $setting]);
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'value' => 'nullable|string',
        ]);

        $setting->update($request->all());

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }
}
