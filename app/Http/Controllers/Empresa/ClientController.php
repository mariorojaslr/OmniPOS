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

        $cliente = Client::create([
            'empresa_id' => $empresaId,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'document' => $request->document,
            'type' => $request->type,
            'tax_condition' => $request->tax_condition ?? null,
            'credit_limit' => $request->credit_limit ?? 0,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'address' => $request->address ?? null,
            'lat' => $request->lat ?? null,
            'lng' => $request->lng ?? null,
            'plus_code' => $request->plus_code ?? null,
            'active' => 1,
            // Plan Med Plus (Afiliados)
            'is_affiliate' => $request->has('is_affiliate'),
            'affiliate_number' => $request->affiliate_number,
            'affiliate_since' => $request->affiliate_since,
            'affiliate_status' => $request->affiliate_status ?? 'active',
            'monthly_fee' => $request->monthly_fee ?? 0,
        ]);

        \App\Models\ActivityLog::log("Creó el cliente: {$cliente->name}");

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

            $movimientos->setCollection($movimientos->getCollection()->reverse());
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
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'plus_code' => $request->plus_code,
            'tax_condition' => $request->tax_condition ?? null,
            'credit_limit' => $request->credit_limit ?? 0,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'active' => $request->active ?? 1,
            // Plan Med Plus (Afiliados)
            'is_affiliate' => $request->has('is_affiliate'),
            'affiliate_number' => $request->affiliate_number,
            'affiliate_since' => $request->affiliate_since,
            'affiliate_status' => $request->affiliate_status,
            'monthly_fee' => $request->monthly_fee ?? 0,
        ]);

        \App\Models\ActivityLog::log("Actualizó el cliente: {$cliente->name}");

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

        $nombreCliente = $cliente->name;
        $cliente->delete();

        \App\Models\ActivityLog::log("Eliminó el cliente: {$nombreCliente}");

        return redirect()
            ->route('empresa.clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORTAR CLIENTES (CSV con ;)
    |--------------------------------------------------------------------------
    */
    public function export()
    {
        $empresaId = auth()->user()->empresa_id;
        $clientes = Client::where('empresa_id', $empresaId)->get();

        $filename = "clientes_empresa_{$empresaId}_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($clientes) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($file, ['ID', 'Nombre', 'Email', 'Teléfono', 'Documento/CUIT', 'Tipo', 'Condición IVA'], ';');

            foreach ($clientes as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->name,
                    $c->email,
                    $c->phone,
                    $c->document,
                    $c->type,
                    $c->tax_condition
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORTAR CLIENTES (CSV con ;)
    |--------------------------------------------------------------------------
    */
    public function import(Request $request)
    {
        $request->validate(['csv_file' => 'required|file']);
        $empresaId = auth()->user()->empresa_id;

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($handle);

        fgetcsv($handle, 1000, ';'); // Cabecera

        $countCreated = 0;
        $countUpdated = 0;

        while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
            if (count($row) < 2) continue;

            $id       = !empty($row[0]) ? $row[0] : null;
            $nombre   = trim($row[1]);
            $email    = trim($row[2] ?? '');
            $telefono = trim($row[3] ?? '');
            $documento = trim($row[4] ?? '');
            $tipo     = trim($row[5] ?? 'Final');
            $iva      = trim($row[6] ?? 'Consumidor Final');

            $client = null;
            if ($id) {
                $client = Client::where('empresa_id', $empresaId)->find($id);
            }
            
            if (!$client && !empty($documento)) {
                $client = Client::where('empresa_id', $empresaId)
                    ->where('document', $documento)
                    ->first();
            }

            if (!$client && !empty($email)) {
                $client = Client::where('empresa_id', $empresaId)
                    ->where('email', $email)
                    ->first();
            }

            $data = [
                'name'  => $nombre,
                'email' => $email,
                'phone' => $telefono,
                'document' => $documento,
                'type'     => $tipo,
                'tax_condition' => $iva,
                'active' => 1
            ];

            if ($client) {
                $client->update($data);
                $countUpdated++;
            } else {
                Client::create(array_merge($data, ['empresa_id' => $empresaId]));
                $countCreated++;
            }
        }

        fclose($handle);

        return back()->with('success', "Proceso terminado: {$countCreated} creados, {$countUpdated} actualizados.");
    }

    /**
     * Obtiene o genera el enlace al Portal del Cliente
     */
    public function getPortalLink(Client $client)
    {
        try {
            $token = $client->portalToken;

            if (!$token) {
                $token = \App\Models\ClientPortalToken::create([
                    'empresa_id' => $client->empresa_id,
                    'client_id' => $client->id,
                    'token' => \Illuminate\Support\Str::random(40),
                ]);
            }

            return response()->json([
                'url' => route('client.portal', ['token' => $token->token])
            ]);
        } catch (\Exception $e) {
            \Log::error("Error generando link de portal para cliente {$client->id}: " . $e->getMessage());
            return response()->json(['error' => 'No se pudo generar el enlace'], 500);
        }
    }

    /**
     * Listado de clientes para gestión de portales
     */
    public function portalList(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $q = $request->get('q');

        $clientes = Client::where('empresa_id', $empresaId)
            ->when($q, function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('document', 'like', "%$q%");
            })
            ->with('portalToken')
            ->orderBy('name')
            ->paginate(50);

        return view('empresa.clientes.portal_list', compact('clientes'));
    }

    public function quickStore(Request $request)
    {
        try {
            $request->validate([
                'name'  => 'required|string|max:255',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:255',
                'lat' => 'nullable',
                'lng' => 'nullable',
            ]);

            $cliente = Client::create([
                'empresa_id' => auth()->user()->empresa_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'type' => 'consumidor_final',
                'document' => null,
                'active' => 1
            ]);

            return response()->json([
                'success' => true,
                'id' => $cliente->id,
                'name' => $cliente->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
