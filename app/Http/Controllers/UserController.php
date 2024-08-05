<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Services\Users\AllUsersService;
use App\Services\Suppliers\AllSuppliersService;
use App\Services\Customers\AllCustomersService;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        private AllUsersService $allUsersService,
        private AllCustomersService $allCustomersService,
        private AllSuppliersService $allSuppliersService
    ) {
    }

    public function index()
    {
        $users = $this->allUsersService->execute();
        $customers = $this->allCustomersService->execute();
        $suppliers = $this->allSuppliersService->execute();

        return Inertia::render('User/Index', [
            'title' => 'Usuarios',
            'users' => $users,
            'customers' => $customers,
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Correcto',
            'user' => $user,
        ]);
    }
}
