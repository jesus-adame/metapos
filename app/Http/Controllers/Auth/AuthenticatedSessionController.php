<?php

namespace App\Http\Controllers\Auth;

use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\CashRegister;
use App\Models\Branch;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        /** @var User */
        $user = Auth::user();

        // Asignar sucursal y caja por defecto si no están asignadas
        if (!$user->branch_id || !$user->cash_register_id) {

            /** @var Branch */
            $defaultBranch = Branch::where('is_default', true)->first();
            $defaultCashRegister = CashRegister::where('is_default', true)
                ->where('branch_id', $defaultBranch->id)
                ->first();

            $user->update([
                'branch_id' => $defaultBranch->id,
                'cash_register_id' => $defaultCashRegister->id,
            ]);
        }

        $request->session()->regenerate();

        // Generar token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devolver el token al cliente
        return response()->json(['token' => $token]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Obtener el usuario autenticado
        $user = $request->user();

        // Revocar el token del usuario
        $user->tokens()->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
