<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\CashRegister;
use App\Models\Branch;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $branches = Branch::all();
        $cashRegisters = CashRegister::with('branch')->get();

        return inertia('Settings/Index', [
            'settings' => $settings,
            'branches' => $branches,
            'cashRegisters' => $cashRegisters,
        ]);
    }

    public function create()
    {
        return inertia('Settings/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'label' => 'required|string|unique:settings,label',
            'value' => 'nullable|string',
        ]);

        Setting::create($request->all());

        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    public function edit(Setting $setting)
    {
        return inertia('Settings/Edit', ['setting' => $setting]);
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
