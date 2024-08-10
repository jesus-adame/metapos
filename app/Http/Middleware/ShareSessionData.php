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
            $user = Auth::user();
            $cashRegister = CashRegister::find($user?->cash_register_id);
            $branch = Branch::find($user?->branch_id);
            $location = $user->location;

            inertia()->share([
                'branch' => $branch,
                'location_id' => $location->id,
                'location_type' => $location::class,
                'location' => $location,
                'branches' => Branch::all(),
                'cashRegister' => $cashRegister,
                'cashRegisters' => CashRegister::all(),
                // Agrega aquí otras variables de sesión que necesites
            ]);
        }

        return $next($request);
    }
}
