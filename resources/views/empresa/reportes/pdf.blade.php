<h2>Reporte Empresa</h2>

<h3>Ranking Productos</h3>
<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>#</th>
        <th>ID</th>
        <th>Producto</th>
        <th>Total</th>
    </tr>
    @foreach($rankingProductos as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->product_id }}</td>
            <td>{{ $item->producto_nombre }}</td>
            <td>{{ $item->total }}</td>
        </tr>
    @endforeach
</table>

<br>

<h3>Ranking Clientes</h3>
<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>#</th>
        <th>Cliente</th>
        <th>Compras</th>
    </tr>
    @foreach($rankingClientes as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->cliente_nombre ?? 'Consumidor final' }}</td>
            <td>{{ $item->total_compras }}</td>
        </tr>
    @endforeach
</table>
