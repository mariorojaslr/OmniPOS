<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CajaCierre;
use App\Models\FinanzaMovimiento;
use App\Models\FinanzaCuenta;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Models\Asistencia;
use Carbon\Carbon;

class CajaAuditDemoSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('role', 'empresa')->first() ?: User::first();
        if (!$user) return;
        
        $empresaId = $user->empresa_id;

        // 1. Cuentas de diversos tipos
        $caja = FinanzaCuenta::firstOrCreate(
            ['empresa_id' => $empresaId, 'nombre' => 'Caja de Ventas'],
            ['tipo' => 'efectivo', 'saldo_actual' => 120500]
        );
        $banco = FinanzaCuenta::firstOrCreate(
            ['empresa_id' => $empresaId, 'nombre' => 'Banco Santander'],
            ['tipo' => 'banco', 'saldo_actual' => 450000]
        );
        $mp = FinanzaCuenta::firstOrCreate(
            ['empresa_id' => $empresaId, 'nombre' => 'Billetera Virtual (MP)'],
            ['tipo' => 'billetera', 'saldo_actual' => 84200]
        );
        $cheques = FinanzaCuenta::firstOrCreate(
            ['empresa_id' => $empresaId, 'nombre' => 'Cheques en Cartera'],
            ['tipo' => 'chequera', 'saldo_actual' => 200000]
        );

        // 2. Crear Asistencia (Turno Largo)
        $inicio = Carbon::now()->subHours(10);
        $fin = Carbon::now()->subMinutes(2);
        
        $asistencia = Asistencia::create([
            'empresa_id' => $empresaId,
            'user_id' => $user->id,
            'entrada' => $inicio,
            'salida' => $fin,
            'vuelto_inicial' => 10000,
            'vuelto_final' => 9850,
        ]);

        // 3. Crear Arqueo de Caja Detallado
        $v_efectivo = 125800;
        $v_digital = 95400; // MP + Banco
        $v_cheques = 50000;
        $egresos = 12500;
        $saldo_inicial = 10000;
        
        $saldo_esperado = $saldo_inicial + $v_efectivo - $egresos;
        $saldo_real = $saldo_esperado - 250; 

        $cierre = CajaCierre::create([
            'empresa_id' => $empresaId,
            'user_id' => $user->id,
            'asistencia_id' => $asistencia->id,
            'fecha_apertura' => $inicio,
            'fecha_cierre' => $fin,
            'saldo_inicial' => $saldo_inicial,
            'ventas_efectivo' => $v_efectivo,
            'ventas_digital' => $v_digital,
            'otros_ingresos' => 5000,
            'egresos' => $egresos,
            'saldo_esperado' => $saldo_esperado,
            'saldo_real' => $saldo_real,
            'diferencia' => -250,
            'observaciones' => 'Turno con gran volumen de ventas. Se realizaron pagos a proveedores en efectivo. Faltan $250 que no se pudieron justificar en el conteo final de billetes mínimos.',
            'estado' => 'cerrada'
        ]);

        // 4. Inyección Masiva de Movimientos (Cronología)
        $movs = [
            ['c' => 'Venta Directa #2024-001', 'm' => 15000, 'acc' => $caja, 'cat' => 'Ventas'],
            ['c' => 'Pago Factura WEB #A12', 'm' => 45000, 'acc' => $banco, 'cat' => 'Ventas Online'],
            ['c' => 'Venta POS MercadoPago', 'm' => 22000, 'acc' => $mp, 'cat' => 'Ventas'],
            ['c' => 'Venta Directa #2024-002', 'm' => 8500, 'acc' => $caja, 'cat' => 'Ventas'],
            ['c' => 'Recibo de Cheque #004566', 'm' => 50000, 'acc' => $cheques, 'cat' => 'Ventas Mayoristas'],
            ['c' => 'Venta Directa #2024-003', 'm' => 12300, 'acc' => $caja, 'cat' => 'Ventas'],
            ['c' => 'Transferencia Recibida CBU', 'm' => 28400, 'acc' => $banco, 'cat' => 'Cobranza'],
            ['c' => 'Ajuste Saldo Positivo', 'm' => 5000, 'acc' => $caja, 'cat' => 'Varios'],
            ['c' => 'Retiro para Compras Limpieza', 'm' => 5000, 'acc' => $caja, 'cat' => 'Retiros', 't' => 'egreso'],
            ['c' => 'Pago de Taxi (Envío)', 'm' => 2500, 'acc' => $caja, 'cat' => 'Gastos', 't' => 'egreso'],
            ['c' => 'Reparación de Aire Ac.', 'm' => 5000, 'acc' => $caja, 'cat' => 'Mantenimiento', 't' => 'egreso'],
        ];

        foreach($movs as $idx => $data) {
            FinanzaMovimiento::create([
                'empresa_id' => $empresaId,
                'cuenta_id' => $data['acc']->id,
                'user_id' => $user->id,
                'tipo' => $data['t'] ?? 'ingreso',
                'monto' => $data['m'],
                'fecha' => $inicio,
                'concepto' => $data['c'],
                'categoria' => $data['cat'],
                'created_at' => $inicio->copy()->addMinutes(($idx + 1) * 45)
            ]);
        }

        // 5. Gastos Oficiales (Expenses)
        $catInsumos = ExpenseCategory::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Insumos']);
        $catServicios = ExpenseCategory::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Servicios']);
        
        Expense::create([
            'empresa_id' => $empresaId, 'user_id' => $user->id, 'asistencia_id' => $asistencia->id,
            'category_id' => $catInsumos->id, 'amount' => 5000, 'date' => $inicio,
            'description' => 'Lavandina, Detergente y Papel', 'provider' => 'Limpieza Total S.A.',
            'created_at' => $inicio->copy()->addHours(2)
        ]);

        Expense::create([
            'empresa_id' => $empresaId, 'user_id' => $user->id, 'asistencia_id' => $asistencia->id,
            'category_id' => $catServicios->id, 'amount' => 7500, 'date' => $inicio,
            'description' => 'Servicio de Cadetería Mensual', 'provider' => 'MotoEnvios Express',
            'created_at' => $inicio->copy()->addHours(6)
        ]);

        $this->command->info("🏁 ¡Auditoría Maestra Generada!");
    }
}
