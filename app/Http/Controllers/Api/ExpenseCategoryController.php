<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $categories = ExpenseCategory::orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($categories);
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        if (is_null($request->code)) {
            return response()->json([]);
        }

        $categories = ExpenseCategory::where('name', 'like', "%$request->code%")
            ->orWhere('description', 'like', "%$request->code%")
            ->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $category = ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Categoría registrado correctamente.',
            'category' => $category,
        ]);
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $expenseCategory->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Categoría actualizado correctamente.',
            'category' => $expenseCategory,
        ]);
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return response()->json([
            'message' => 'Categoría eliminado correctamente.',
        ]);
    }
}
