<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\CashCuts\CreateCashCutService;
use App\Models\CashCut;
use App\Http\Controllers\Controller;

class CashCutController extends Controller
{
    public function index(Request $request)
    {
        $cashRegister = Auth::user()->cashRegister;

        $perPage = $request->input('rows', 10);
        $cashCuts = CashCut::with('cashRegister')
            ->where('cash_register_id', $cashRegister->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($cashCuts);
    }

    public function store(Request $request, CreateCashCutService $service)
    {
        $request->validate([
            'cut_date' => 'required|date',
            'cut_end_date' => 'required|date',
            'timezone' => 'required|string',
        ]);

        $cashRegister = Auth::user()->cashRegister;

        // Calcular entradas y salidas hasta la fecha del corte
        $cutDate = Carbon::createFromTimeString($request->cut_date);
        $cutEndDate = Carbon::createFromTimeString($request->cut_end_date);

        $cashCut = $service->execute($cutDate, $cutEndDate, $cashRegister);

        return response()->json([
            'message' => 'Corte de caja registrado.',
            'cash_cut' => $cashCut,
        ]);
    }

    public function destroy(CashCut $cashCut)
    {
        $cashCut->delete();

        return response()->json([
            'message' => 'Eliminado correctamente',
        ]);
    }
}
