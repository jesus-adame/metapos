<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;
use App\Models\User;
use App\Models\Location;
use App\Models\CashRegister;

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
                /** @var Location */
                $location = Location::where('is_default', true)->first();

                $defaultCashRegister = CashRegister::where('is_default', true)
                    ->where('location_id', $location?->id)
                    ->first();

                if (!is_null($defaultCashRegister)) {
                    $user->update([
                        'location_id' => $location->id,
                        'cash_register_id' => $defaultCashRegister->id,
                    ]);
                }
            }

            inertia()->share([
                'location_id' => $location?->id,
                'location' => $location,
                'locations' => Location::all(),
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
