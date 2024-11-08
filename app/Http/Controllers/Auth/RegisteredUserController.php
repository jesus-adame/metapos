<?php

namespace App\Http\Controllers\Auth;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Role;
use App\Models\Location;
use App\Models\CashRegister;
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
        /** @var Location | null */
        $defaultLocation = Location::where('is_default', true)->first();

        if (is_null($defaultLocation)) {
            /** @var Location */
            $defaultLocation = Location::create([
                'name' => $request->location,
                'address' => $request->address,
                'type' => 'branch',
                'is_default' => true,
                'timezone' => 'America/Mexico_City'
            ]);
        }

        /** @var CashRegister | null */
        $defaulCashRegister = CashRegister::where('is_default', true)->first();

        if (is_null($defaulCashRegister)) {
            $defaulCashRegister = CashRegister::create([
                'name' => 'Mostrador',
                'location_id' => $defaultLocation->id,
                'is_default' => true
            ]);
        }

        /** @var User */
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'location_id' => $defaultLocation->id,
            'cash_register_id' => $defaulCashRegister->id,
        ]);

        $user->assignRole(Role::MANAGER);

        event(new Registered($user));

        return response()->json(['message' => 'Usuario registrado correctamente.']);
    }
}
