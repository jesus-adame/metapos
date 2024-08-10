<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashFlow;

class CashFlowController extends Controller
{
    public function index()
    {
        return inertia('CashFlows/Index');
    }

    public function create()
    {
        return inertia('CashFlows/Create');
    }
}
