<?php

namespace App\Http\Controllers\Auth;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\CashRegister;
use App\Models\Branch;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\Controller;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return inertia('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): Response
    {
        $branch = Branch::create([
            'name' => $request->branch,
            'address' => $request->address,
            'type' => 'branch',
            'is_default' => true
        ]);

        $cashRegister = CashRegister::create([
            'name' => 'Mostrador',
            'branch_id' => $branch->id,
            'is_default' => true
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'location_id' => $branch->id,
            'location_type' => $branch::class,
            'cash_register_id' => $cashRegister->id,
        ]);

        event(new Registered($user));

        return response()->json(['message' => 'Usuario registrado correctamente.']);
    }
}
