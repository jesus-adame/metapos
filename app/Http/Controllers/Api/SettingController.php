<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Permission;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize(Permission::VIEW_SETTINGS);

        $settings = Setting::all();

        return response()->json($settings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'settings' => 'array',
        ]);

        foreach ($request->settings as $arrSetting) {
            $setting = Setting::where('key', $arrSetting['key'])->first();

            if (isset($arrSetting['value'])) {
                $setting->value = $arrSetting['value'];
            }

            $setting->save();
        }

        return response()->json([
            'message' => 'Ajustes actualizados correctamente.'
        ]);
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'settings' => 'array',
        ]);

        $setting->update([
            'value' => $request->value,
        ]);

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }
}
