<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use App\Models\Sale;

class SalesExport implements FromView
{
    protected $startDate;
    protected $endDate;
    protected $filters;

    public function __construct(array $filters, ?Carbon $since, ?Carbon $until)
    {
        $this->startDate = $since;
        $this->endDate = $until;
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $builder = Sale::with(['customer', 'seller', 'cashRegister']);

        if (!is_null($this->startDate) && !is_null($this->endDate)) {
            $builder
                ->whereBetween('created_at', [$this->startDate->utc(), $this->endDate->utc()]);
        }

        if (isset($this->filters['cash_register'])) {
            $builder->where('cash_register_id', $this->filters['cash_register']);
        }

        if (isset($this->filters['status'])) {
            $builder->where('status', $this->filters['status']);
        }

        $sales = $builder
            ->orderBy('id', 'desc')
            ->get();

        $auth = Auth::user();

        return view('exports.sales', ['sales' => $sales, 'auth' => $auth]);
    }
}
