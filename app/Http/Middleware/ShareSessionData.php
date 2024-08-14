<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;
use App\Models\User;
use App\Models\CashRegister;
use App\Models\Branch;

class ShareSessionData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User */
            $user = $request->user();
            $cashRegister = CashRegister::find($user?->cash_register_id);
            $location = $user->location;

            if (is_null($location)) {
                /** @var Branch */
                $location = Branch::where('is_default', true)->first();

                $defaultCashRegister = CashRegister::where('is_default', true)
                    ->where('branch_id', $location->id)
                    ->first();

                $user->update([
                    'location_id' => $location->id,
                    'location_type' => $location::class,
                    'cash_register_id' => $defaultCashRegister->id,
                ]);

                $request->session()->regenerate();
            }

            inertia()->share([
                'location_id' => $location?->id,
                'location_type' => $location::class,
                'location' => $location,
                'branches' => Branch::all(),
                'cashRegister' => $cashRegister,
                'cashRegisters' => CashRegister::all(),
                'roles' => $request->user() ? $request->user()->roles->pluck('name') : [],
                'permissions' => $request->user() ? $request->user()->getAllPermissions()->pluck('name') : [],
                // Agrega aquí otras variables de sesión que necesites
            ]);
        }

        return $next($request);
    }
}
