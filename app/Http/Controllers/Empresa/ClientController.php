<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientLedger;

class ClientController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTADO DE CLIENTES
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $perPage = $request->get('perPage', 15);
        $q       = $request->get('q');

        $query = Client::where('empresa_id', $empresaId);

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('document', 'like', "%$q%");
            });
        }

        if ($request->has('ajax')) {
            return response()->json(
                $query->orderBy('name')->limit($perPage)->get()
            );
        }

        $clientes = $query
            ->orderBy('name')
            ->paginate($perPage)
            ->appends($request->query());

        return view('empresa.clientes.index', compact('clientes'));
    }



    public function create()
    {
        return view('empresa.clientes.create');
    }



    /*
    |--------------------------------------------------------------------------
    | CREAR CLIENTE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'document' => 'nullable|string|max:20',
            'type'  => 'required|string',
        ]);

        // 🔒 Evitar duplicar Consumidor Final
        if ($request->document === 'CF') {
            $existeCF = Client::where('empresa_id', $empresaId)
                ->where('document', 'CF')
                ->exists();

            if ($existeCF) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Ya existe el cliente CONSUMIDOR FINAL.');
            }
        }

        Client::create([
            'empresa_id' => $empresaId,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'document' => $request->document,
            'type' => $request->type,
            'tax_condition' => $request->tax_condition ?? null,
            'credit_limit' => $request->credit_limit ?? 0,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'active' => 1
        ]);

        return redirect()
            ->route('empresa.clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }



    /*
    |--------------------------------------------------------------------------
    | CUENTA CORRIENTE - MOTOR CONTABLE
    |--------------------------------------------------------------------------
    | Orden real:
    | 1) Fecha más reciente primero
    | 2) ID más alto primero (garantiza última factura arriba)
    |--------------------------------------------------------------------------
    */
    public function show(Request $request, string $id)
    {
        $empresaId = auth()->user()->empresa_id;

        $cliente = Client::where('empresa_id', $empresaId)
            ->findOrFail($id);

        $perPage = $request->get('perPage', 25);
        $fechaCorte = $request->get('corte');

        /*
        |--------------------------------------------------------------------------
        | SALDO TOTAL REAL (SQL DIRECTO)
        |--------------------------------------------------------------------------
        */
        $saldoQuery = ClientLedger::where('empresa_id', $empresaId)
            ->where('client_id', $cliente->id);

        if ($fechaCorte) {
            $saldoQuery->whereDate('created_at', '<=', $fechaCorte);
        }

        $saldo = $saldoQuery
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type='debit' THEN amount END),0)
              - COALESCE(SUM(CASE WHEN type='credit' THEN amount END),0)
              AS saldo
            ")
            ->value('saldo');


        /*
        |--------------------------------------------------------------------------
        | QUERY BASE CON FILTROS
        |--------------------------------------------------------------------------
        */
        $queryBase = ClientLedger::where('empresa_id', $empresaId)
            ->where('client_id', $cliente->id)
            ->when($request->tipo, fn($q) => $q->where('type', $request->tipo))
            ->when($request->desde, fn($q) => $q->whereDate('created_at', '>=', $request->desde))
            ->when($request->hasta, fn($q) => $q->whereDate('created_at', '<=', $request->hasta));

        if ($fechaCorte) {
            $queryBase->whereDate('created_at', '<=', $fechaCorte);
        }

        /*
        |--------------------------------------------------------------------------
        | 🔥 ORDEN CORRECTO (ÚLTIMA FACTURA PRIMERO)
        |--------------------------------------------------------------------------
        */
        $movimientos = $queryBase
            ->orderByDesc('created_at')  // Fecha descendente
            ->orderByDesc('id')          // Seguridad por ID
            ->paginate($perPage)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | CÁLCULO ACUMULADO (NO SE TOCA TU LÓGICA)
        |--------------------------------------------------------------------------
        */
        if ($movimientos->count()) {

            $ultimoMovimiento = $movimientos->last();

            $saldoInicial = ClientLedger::where('empresa_id', $empresaId)
                ->where('client_id', $cliente->id)
                ->where(function ($q) use ($ultimoMovimiento) {
                    $q->where('created_at', '<', $ultimoMovimiento->created_at)
                      ->orWhere(function ($q2) use ($ultimoMovimiento) {
                          $q2->where('created_at', $ultimoMovimiento->created_at)
                             ->where('id', '<', $ultimoMovimiento->id);
                      });
                })
                ->selectRaw("
                    COALESCE(SUM(CASE WHEN type='debit' THEN amount END),0)
                  - COALESCE(SUM(CASE WHEN type='credit' THEN amount END),0)
                  AS saldo
                ")
                ->value('saldo');

            $saldoTemp = $saldoInicial;

            foreach ($movimientos->reverse() as $m) {

                $saldoTemp += ($m->type === 'debit') ? $m->amount : -$m->amount;

                $m->saldo_acumulado = $saldoTemp;
                $m->debe  = $m->type === 'debit' ? $m->amount : null;
                $m->haber = $m->type === 'credit' ? $m->amount : null;
            }

            $movimientos->setCollection($movimientos->reverse());
        }


        /*
        |--------------------------------------------------------------------------
        | SALDO VENCIDO
        |--------------------------------------------------------------------------
        */
        $saldoVencido = ClientLedger::where('empresa_id', $empresaId)
            ->where('client_id', $cliente->id)
            ->where('type', 'debit')
            ->where('paid', false)
            ->whereDate('created_at', '<', now()->subDays(30))
            ->sum('amount');

        return view('empresa.clientes.show', compact(
            'cliente',
            'movimientos',
            'saldo',
            'saldoVencido',
            'fechaCorte'
        ));
    }



    /*
    |--------------------------------------------------------------------------
    | EDITAR CLIENTE (PROTECCIÓN CF)
    |--------------------------------------------------------------------------
    */
    public function edit(string $id)
    {
        $cliente = Client::where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($id);

        if ($cliente->document === 'CF') {
            return redirect()
                ->route('empresa.clientes.index')
                ->with('error', 'El cliente CONSUMIDOR FINAL no puede editarse.');
        }

        return view('empresa.clientes.edit', compact('cliente'));
    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE CLIENTE (PROTECCIÓN CF)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, string $id)
    {
        $cliente = Client::where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($id);

        if ($cliente->document === 'CF') {
            return redirect()
                ->route('empresa.clientes.index')
                ->with('error', 'El cliente CONSUMIDOR FINAL no puede modificarse.');
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'type'  => 'required|string',
        ]);

        $cliente->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'document' => $request->document,
            'type' => $request->type,
            'tax_condition' => $request->tax_condition ?? null,
            'credit_limit' => $request->credit_limit ?? 0,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'active' => $request->active ?? 1,
        ]);

        return redirect()
            ->route('empresa.clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }



    /*
    |--------------------------------------------------------------------------
    | DELETE CLIENTE (PROTECCIÓN CF)
    |--------------------------------------------------------------------------
    */
    public function destroy(string $id)
    {
        $cliente = Client::where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($id);

        if ($cliente->document === 'CF') {
            return redirect()
                ->route('empresa.clientes.index')
                ->with('error', 'El cliente CONSUMIDOR FINAL no puede eliminarse.');
        }

        $cliente->delete();

        return redirect()
            ->route('empresa.clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
