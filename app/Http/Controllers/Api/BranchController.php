<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('rows', 10);
        $users = Branch::orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'type' => 'required|string|in:branch,warehouse', // Validate type
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'type' => 'required|string|in:branch,warehouse', // Validate type
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
