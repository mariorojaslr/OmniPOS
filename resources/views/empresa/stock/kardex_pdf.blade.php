<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:6px; }
        th { background:#eee; }
    </style>
</head>
<body>

<h2>Kardex — {{ $product->name }}</h2>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Stock</th>
            <th>Origen</th>
        </tr>
    </thead>
    <tbody>
    @foreach($movimientos as $m)
        <tr>
            <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ ucfirst($m->tipo) }}</td>
            <td>{{ $m->cantidad }}</td>
            <td>{{ $m->stock_resultante }}</td>
            <td>{{ $m->origen }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
