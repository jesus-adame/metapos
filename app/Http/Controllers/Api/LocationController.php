<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('rows', 10);
        $users = Location::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'type' => 'required|string|in:branch,warehouse',
            'timezone' => 'required|timezone:all',
            'currency' => 'required|exists:currencies,code',
        ]);

        Location::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'rfc' => $request->rfc,
            'type' => 'branch',
            'is_default' => false,
            'currency' => $request->currency,
        ]);

        return response()->json([
            'message' => 'Ubicación registrada.'
        ]);
    }

    public function update(Request $request, Location $location): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'type' => 'required|string|in:branch,warehouse', // Validate type
            'timezone' => 'required|timezone:all',
            'currency' => 'required|exists:currencies,code',
        ]);

        $location->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'rfc' => $request->rfc,
            'type' => $request->type,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
        ]);

        return response()->json([
            'message' => 'Ubicación actualizada.'
        ]);
    }

    public function destroy(Location $location): JsonResponse
    {
        if ($location->is_default) {
            return response()->json([
                'message' => 'No se pueden eliminar datos por defecto.'
            ], JsonResponse::HTTP_BAD_GATEWAY);
        }

        $location->delete();

        return response()->json([
            'message' => 'Ubicación eliminada.'
        ]);
    }
}
