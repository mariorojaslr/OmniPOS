<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClientLedger;
use App\Models\Recibo;
use App\Models\ReciboImputacion;
use App\Models\Client;

$clientId = 12; // CLIENTES DEMOSTRACIONES
$empresaId = 1;

$client = Client::find($clientId);
if (!$client) {
    die("Cliente no encontrado");
}

// Limpiar historial previo
ClientLedger::where('client_id', $clientId)->delete();
Recibo::where('client_id', $clientId)->delete();

// 1. FACTURA CANCELADA TOTALMENTE
$v1 = ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'debit', 'amount' => 10000, 'pending_amount' => 0, 'paid' => true, 'description' => 'Factura A-001 (Pagada)']);
$r1 = Recibo::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'user_id' => 1, 'numero_recibo' => 'R-0001-00000100', 'monto_total' => 10000, 'fecha' => now(), 'metodo_pago' => 'Efectivo']);
$l1 = ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'credit', 'amount' => 10000, 'pending_amount' => 0, 'paid' => true, 'description' => 'Recibo #100', 'reference_type' => Recibo::class, 'reference_id' => $r1->id]);
ReciboImputacion::create(['recibo_id' => $r1->id, 'ledger_id' => $v1->id, 'monto_aplicado' => 10000]);

// 2. FACTURA PENDIENTE TOTAL
ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'debit', 'amount' => 25000, 'pending_amount' => 25000, 'paid' => false, 'description' => 'Factura A-002 (Pendiente)']);

// 3. FACTURA PAGO PARCIAL (Quedan 20k)
$v3 = ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'debit', 'amount' => 50000, 'pending_amount' => 20000, 'paid' => false, 'description' => 'Factura A-003 (Pago Parcial)']);
$r3 = Recibo::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'user_id' => 1, 'numero_recibo' => 'R-0001-00000103', 'monto_total' => 30000, 'fecha' => now(), 'metodo_pago' => 'Transferencia']);
ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'credit', 'amount' => 30000, 'pending_amount' => 0, 'paid' => true, 'description' => 'Recibo #103', 'reference_type' => Recibo::class, 'reference_id' => $r3->id]);
ReciboImputacion::create(['recibo_id' => $r3->id, 'ledger_id' => $v3->id, 'monto_aplicado' => 30000]);

// 4. RECIBO HUÉRFANO (15k disponibles)
$r4 = Recibo::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'user_id' => 1, 'numero_recibo' => 'R-0001-00000104', 'monto_total' => 15000, 'fecha' => now(), 'metodo_pago' => 'Efectivo']);
ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'credit', 'amount' => 15000, 'pending_amount' => 15000, 'paid' => false, 'description' => 'Pago a cuenta (Huérfano)', 'reference_type' => Recibo::class, 'reference_id' => $r4->id]);

// 5. RECIBO PAGO PARCIAL (Tiene 100k, usó 40k, quedan 60k)
$v5 = ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'debit', 'amount' => 40000, 'pending_amount' => 0, 'paid' => true, 'description' => 'Factura A-005 (Cancelada)']);
$r5 = Recibo::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'user_id' => 1, 'numero_recibo' => 'R-0001-00000105', 'monto_total' => 100000, 'fecha' => now(), 'metodo_pago' => 'Múltiple']);
ClientLedger::create(['empresa_id' => $empresaId, 'client_id' => $clientId, 'type' => 'credit', 'amount' => 100000, 'pending_amount' => 60000, 'paid' => false, 'description' => 'Recibo Gigante #105', 'reference_type' => Recibo::class, 'reference_id' => $r5->id]);
ReciboImputacion::create(['recibo_id' => $r5->id, 'ledger_id' => $v5->id, 'monto_aplicado' => 40000]);

echo "OK";
