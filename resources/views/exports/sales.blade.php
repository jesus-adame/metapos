<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th>Caja Registadora</th>
            <th>Estatus</th>
            <th>Cambio</th>
            <th>Total</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->customer?->name }} {{ $sale->customer?->lastname }}</td>
                <td>{{ $sale->seller?->name }} {{ $sale->seller?->lastname }}</td>
                <td>{{ $sale->cashRegister?->name }}</td>
                <td>{{ $sale->status }}</td>
                <td>{{ $sale->change }}</td>
                <td>{{ $sale->total }}</td>
                <td>{{ Carbon\Carbon::parse($sale->created_at, $auth->timezone) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>