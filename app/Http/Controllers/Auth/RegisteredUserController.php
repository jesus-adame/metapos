<?php

namespace App\Http\Controllers\Auth;

use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
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
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
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
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch_id' => $branch->id,
            'cash_register_id' => $cashRegister->id,
        ]);

        event(new Registered($user));

        return redirect(route('dashboard', absolute: false));
    }
}
