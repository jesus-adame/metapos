<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\CashCuts\CreateCashCutService;
use App\Models\CashRegister;
use App\Models\CashCut;
use App\Http\Controllers\Controller;

class CashCutController extends Controller
{
    public function index(Request $request)
    {
        $cashRegister = Auth::user()->cashRegister;

        $perPage = $request->input('rows', 10);
        $cashCuts = CashCut::with(['cashRegister', 'user'])
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
            'cash' => 'required|numeric',
            'card' => 'required|numeric',
            'transfer' => 'required|numeric',
        ]);

        /** @var CashRegister */
        $cashRegister = Auth::user()->cashRegister;

        $splitSince = explode('T', $request->cut_date);
        $splitUntil = explode('T', $request->cut_end_date);

        // Calcular entradas y salidas hasta la fecha del corte
        $cutDate = Carbon::parse($splitSince[0] . ' 00:00:00 ' . $request->timezone);
        $cutEndDate = Carbon::parse($splitUntil[0] . ' 23:59:59 ' . $request->timezone);

        $cashCut = $service->execute(Auth::user(), $cutDate->utc(), $cutEndDate->utc(), $cashRegister, $request->all());

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
