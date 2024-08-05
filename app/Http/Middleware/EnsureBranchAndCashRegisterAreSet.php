<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;
use App\Models\User;
use App\Models\Branch;

class EnsureBranchAndCashRegisterAreSet
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

            if (!$user->branch_id || !$user->cash_register_id) {
                // Asignar sucursal y caja por defecto si no están asignadas
                $defaultBranch = Branch::first();
                $defaultCashRegister = $defaultBranch?->cashRegisters()?->first();

                $user->update([
                    'branch_id' => $defaultBranch?->id,
                    'cash_register_id' => $defaultCashRegister?->id,
                ]);
            }
        }

        return $next($request);
    }
}
