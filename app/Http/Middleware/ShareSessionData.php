<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;
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
        if (auth()->check()) {
            $user = auth()->user();
            $cashRegister = CashRegister::find($user?->cash_register_id);
            $branch = Branch::find($user?->branch_id);

            inertia()->share([
                'branch' => $branch,
                'branches' => Branch::all(),
                'cashRegister' => $cashRegister,
                'cashRegisters' => CashRegister::all(),
                // Agrega aquí otras variables de sesión que necesites
            ]);
        }

        return $next($request);
    }
}
