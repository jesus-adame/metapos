<?php

namespace App\Http\Controllers\Central;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Http\Controllers\Controller;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')
            ->orderBy('created_at', 'desc')
            ->get();

        return inertia('Central/Tenants/Index', [
            'tenants' => $tenants
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Central/Tenants/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|alpha_num:ascii|unique:domains,id',
            'company' => 'required|string|max:30',
        ]);

        $domainUrl = strtolower($request->input('domain')) . '.' . config('tenancy.app_domain');
        $tenantId = Str::uuid() . '_' . strtolower(str_replace(' ', '_', $request->input('domain')));
        $companyName = $request->input('company');

        /** @var Tenant */
        $tenant = Tenant::create([
            'id' => $tenantId,
            'company' => $companyName,
        ]);

        $tenant->domains()->create(['domain' => $domainUrl]);

        return redirect()->route('tenants.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('tenants.index');
    }
}
