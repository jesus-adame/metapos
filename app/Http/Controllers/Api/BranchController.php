<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('rows', 10);
        $users = Branch::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Branch::create([
            'name' => $request->name,
            'address' => $request->address,
            'type' => 'branch',
            'is_default' => false,
        ]);

        return response()->json([
            'message' => 'Ubicación registrada.'
        ]);
    }

    public function update(Request $request, Branch $branch): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'type' => 'required|string|in:branch,warehouse', // Validate type
        ]);

        $branch->update($request->all());

        return response()->json([
            'message' => 'Ubicación actualizada.'
        ]);
    }

    public function destroy(Branch $branch): JsonResponse
    {
        if ($branch->is_default) {
            return response()->json([
                'message' => 'No se pueden eliminar datos por defecto.'
            ], JsonResponse::HTTP_BAD_GATEWAY);
        }

        $branch->delete();

        return response()->json([
            'message' => 'Ubicación eliminada.'
        ]);
    }
}
