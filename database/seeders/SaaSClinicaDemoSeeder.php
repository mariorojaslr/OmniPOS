<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Empresa;
use App\Models\EmpresaConfig;
use App\Models\User;
use App\Models\Client;
use App\Models\MedicalRecord;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Rubro;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SaaSClinicaDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener o Crear Plan Pro
        $planPro = Plan::firstOrCreate(
            ['name' => 'Plan Pro'],
            [
                'description' => 'Ideal para centros médicos en expansión',
                'price' => 25000,
                'max_users' => 50,
                'max_products' => 1000,
                'max_storage_mb' => 5120, // 5GB
                'is_active' => true,
            ]
        );

        // 2. Obtener o Crear Revendedor de prueba
        $revendedor = User::firstOrCreate(
            ['email' => 'revendedor@poolhub.com'],
            [
                'name' => 'Revendedor Principal',
                'password' => Hash::make('poolhub2026'),
                'role' => 'revendedor',
                'activo' => true,
                'status' => 'activo',
            ]
        );

        // 3. Crear Empresa de Demostración
        $empresaDemo = Empresa::updateOrCreate(
            ['slug' => 'clinica-demo'],
            [
                'nombre_comercial' => 'Sanatorio del Sol (Demo)',
                'razon_social' => 'Sanatorio del Sol S.A.',
                'email' => 'clinica-demo@poolhub.com',
                'telefono' => '3519876543',
                'activo' => true,
                'plan_id' => $planPro->id,
                'reseller_id' => $revendedor->id,
                'status' => 'activa',
                'fecha_vencimiento' => now()->addYears(2),
            ]
        );

        // 4. Configuración Visual & Módulos para la Demo (Estilo OLED Dark por defecto)
        EmpresaConfig::updateOrCreate(
            ['empresa_id' => $empresaDemo->id],
            [
                'color_primary' => '#6366f1', // Indigo Premium
                'color_secondary' => '#10b981', // Emerald Accent
                'logo' => null, 
                'mod_turnos' => true,
                'mod_hce' => true,
                'mod_campos_dinamicos' => true, // Activo!
                'mod_afiliados' => true,
                'mod_afip' => true,
                'mod_pagos' => true,
                'mod_backups' => true,
                'mod_internaciones' => true, // Activo!
                'theme' => 'light', // Modo Claro por defecto para mostrar los colores corporativos
            ]
        );

        // 5. Crear Usuarios (Administrador y Médico Profesional)
        $adminUser = User::updateOrCreate(
            ['email' => 'demo@poolhub.com'],
            [
                'name' => 'Dr. Leandro Gómez (Admin Demo)',
                'password' => Hash::make('poolhub2026'),
                'role' => 'empresa',
                'empresa_id' => $empresaDemo->id,
                'activo' => true,
                'status' => 'activo',
            ]
        );

        $doctorUser = User::updateOrCreate(
            ['email' => 'medico@poolhub.com'],
            [
                'name' => 'Dra. Sofía Martínez (Cardiología)',
                'password' => Hash::make('poolhub2026'),
                'role' => 'usuario',
                'sub_role' => 'empleado',
                'empresa_id' => $empresaDemo->id,
                'activo' => true,
                'status' => 'activo',
            ]
        );

        // 6. Obras Sociales (Demostración)
        DB::table('obras_sociales')->where('empresa_id', $empresaDemo->id)->delete();
        $osdeId = DB::table('obras_sociales')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'OSDE 310',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $swissId = DB::table('obras_sociales')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Swiss Medical',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $galenoId = DB::table('obras_sociales')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Galeno Oro',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. Clientes / Pacientes de Demostración
        DB::table('clients')->where('empresa_id', $empresaDemo->id)->delete();
        
        $pacienteCarlosId = DB::table('clients')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'name' => 'Carlos Mendoza',
            'email' => 'carlos.mendoza@email.com',
            'phone' => '3514561230',
            'document' => '24111222',
            'is_affiliate' => 1,
            'obra_social_id' => $osdeId,
            'affiliate_number' => '15-485962-3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pacienteAmbarId = DB::table('clients')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'name' => 'Ámbar Gómez (Infante)',
            'email' => 'gomez.padre@email.com',
            'phone' => '3517894561',
            'document' => '54888999',
            'is_affiliate' => 1,
            'obra_social_id' => $galenoId,
            'affiliate_number' => 'GAL-99885-02',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pacienteSofiaId = DB::table('clients')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'name' => 'Sofía Rodríguez',
            'email' => 'sofia.rodriguez@email.com',
            'phone' => '3511593578',
            'document' => '32777888',
            'is_affiliate' => 1,
            'obra_social_id' => $swissId,
            'affiliate_number' => 'SM-48596-98',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Historias Clínicas (HCE) con Campos Dinámicos JSON (Med Plus & Demo tags)
        DB::table('medical_records')->where('empresa_id', $empresaDemo->id)->delete();

        // Registro 1: Odontología con Tags Dinámicos
        MedicalRecord::create([
            'empresa_id' => $empresaDemo->id,
            'client_id' => $pacienteAmbarId,
            'user_id' => $adminUser->id,
            'specialty' => 'Odontopediatría',
            'reason_for_visit' => 'Revisión semestral y molestias leves en zona molar.',
            'diagnosis' => "Se observa caries activa de segundo grado en pieza temporaria molar inferior izquierda.\nResto de piezas dentales sanas sin anomalías de oclusión.",
            'treatment' => "Se realiza limpieza profunda profiláctica.\nSe programa sesión para obturación con resina estética fotocurable.",
            'internal_notes' => 'El paciente se muestra ansioso. Se sugiere técnica de manejo conductual para niños en próxima consulta.',
            'specialized_data' => [
                'vitals' => [
                    'sys' => '', 'dia' => '', 'glucose' => '', 'heart' => '',
                    'weight' => '24'
                ],
                'medications' => [
                    [
                        'name' => 'Ibuprofeno 4% Suspensión',
                        'dose' => '6 ml',
                        'frequency' => 'cada 8 horas si presenta dolor residual',
                        'route' => 'Oral'
                    ]
                ],
                'labs' => [
                    'hemo' => '', 'red' => '', 'white' => '', 'chol' => ''
                ],
                'dynamic_fields' => [
                    'Pieza Dental' => '74 (Segundo Molar Inferior Izq)',
                    'Urgencia' => 'Moderada',
                    'Tipo de Caries' => 'Dentina superficial activa',
                    'Higiene Bucal' => 'Buena (se refuerza técnica de cepillado)'
                ]
            ]
        ]);

        // Registro 2: Cardiología con Votación Completa
        MedicalRecord::create([
            'empresa_id' => $empresaDemo->id,
            'client_id' => $pacienteCarlosId,
            'user_id' => $doctorUser->id,
            'specialty' => 'Cardiología',
            'reason_for_visit' => 'Control de rutina post-arritmia y fatiga crónica.',
            'diagnosis' => "Ritmo sinusal regular. No se auscultan soplos. Eje eléctrico normal.\nLeve extrasistolia ventricular esporádica sin repercusión hemodinámica.",
            'treatment' => "Continuar con Atenolol diario.\nReducir ingesta de sodio. Ejercicios aeróbicos livianos 3 veces por semana.",
            'internal_notes' => 'Paciente colaborador. Trae bitácora de presión arterial en rango normal.',
            'specialized_data' => [
                'vitals' => [
                    'sys' => '130',
                    'dia' => '82',
                    'glucose' => '98',
                    'heart' => '72',
                    'weight' => '84'
                ],
                'medications' => [
                    [
                        'name' => 'Atenolol 50mg',
                        'dose' => '1 comprimido',
                        'frequency' => 'cada 24 horas (mañana)',
                        'route' => 'Oral'
                    ],
                    [
                        'name' => 'Aspirina Prevent 100mg',
                        'dose' => '1 comprimido',
                        'frequency' => 'cada 24 horas (almuerzo)',
                        'route' => 'Oral'
                    ]
                ],
                'labs' => [
                    'hemo' => '15.2',
                    'red' => '5.1',
                    'white' => '6800',
                    'chol' => '210'
                ],
                'dynamic_fields' => [
                    'Riesgo Cardiovascular' => 'Moderado-Alto',
                    'Electrocardiograma' => 'Ritmo sinusal, FC 72 lpm, sin signos de isquemia aguda',
                    'Próxima Visita' => 'En 6 meses con nuevo laboratorio'
                ]
            ]
        ]);

        // Registro 3: Kinesiología
        MedicalRecord::create([
            'empresa_id' => $empresaDemo->id,
            'client_id' => $pacienteSofiaId,
            'user_id' => $adminUser->id,
            'specialty' => 'Kinesiología / Fisioterapia',
            'reason_for_visit' => 'Rehabilitación post-esguince de tobillo grado 2.',
            'diagnosis' => "Edema residual leve perimaleolar externo en tobillo derecho.\nLimitación en flexión dorsal del tobillo (rango de movimiento 75%). Dolor a la inversión pasiva.",
            'treatment' => "Movilizaciones articulares pasivas y asistidas.\nUltrasonido terapéutico 10 min + magnetoterapia.\nEjercicios de propiocepción en plano inestable.",
            'internal_notes' => 'Evolución favorable. Segunda sesión de diez indicadas.',
            'specialized_data' => [
                'vitals' => [
                    'sys' => '', 'dia' => '', 'glucose' => '', 'heart' => '',
                    'weight' => '62'
                ],
                'medications' => [],
                'labs' => [
                    'hemo' => '', 'red' => '', 'white' => '', 'chol' => ''
                ],
                'dynamic_fields' => [
                    'Articulación' => 'Tobillo Derecho',
                    'Sesión Nro' => '2 de 10',
                    'Dolor Escala EVA' => '4/10',
                    'Edema' => 'Leve (+1/4)'
                ]
            ]
        ]);

        // 9. Poblado de Habitaciones & Camas (Hospitalización de prueba)
        DB::table('internacion_camas')->delete();
        DB::table('internaciones')->where('empresa_id', $empresaDemo->id)->delete();
        DB::table('camas')->where('empresa_id', $empresaDemo->id)->delete();
        DB::table('habitaciones')->where('empresa_id', $empresaDemo->id)->delete();

        $salaUti = DB::table('habitaciones')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Sala Unidad de Terapia Intensiva (UTI)',
            'tipo' => 'terapia_intensiva',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $salaPiso = DB::table('habitaciones')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Habitación Piso General 104',
            'tipo' => 'comun',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Camas UTI
        $camaUtiA = DB::table('camas')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'habitacion_id' => $salaUti,
            'nombre' => 'Cama UTI-01',
            'estado' => 'ocupada', // Ocupada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $camaUtiB = DB::table('camas')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'habitacion_id' => $salaUti,
            'nombre' => 'Cama UTI-02',
            'estado' => 'libre',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Camas Piso General
        $camaPisoA = DB::table('camas')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'habitacion_id' => $salaPiso,
            'nombre' => 'Cama 104-A',
            'estado' => 'ocupada', // Ocupada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $camaPisoB = DB::table('camas')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'habitacion_id' => $salaPiso,
            'nombre' => 'Cama 104-B',
            'estado' => 'libre',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Registrar las internaciones activas (Carlos Mendoza en UTI)
        $intCarlosId = DB::table('internaciones')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'client_id' => $pacienteCarlosId,
            'fecha_ingreso' => now()->subDays(2)->format('Y-m-d H:i:s'),
            'estado' => 'activo',
            'motivo_ingreso' => 'Dolor de pecho precordial agudo.',
            'diagnostico_ingreso' => 'Monitoreo post-angioplastia coronaria de emergencia y extrasistolia recurrente.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('internacion_camas')->insert([
            'internacion_id' => $intCarlosId,
            'cama_id' => $camaUtiA,
            'fecha_desde' => now()->subDays(2)->format('Y-m-d H:i:s'),
            'tipo_internacion' => 'terapia_intensiva',
            'notas' => 'Equipada con respirador monopala y monitoreo continuo.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Registrar Sofía Rodríguez en Piso General
        $intSofiaId = DB::table('internaciones')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'client_id' => $pacienteSofiaId,
            'fecha_ingreso' => now()->subDays(1)->format('Y-m-d H:i:s'),
            'estado' => 'activo',
            'motivo_ingreso' => 'Recuperación post-quirúrgica.',
            'diagnostico_ingreso' => 'Postoperatorio de artroscopia reconstructiva de ligamentos del tobillo.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('internacion_camas')->insert([
            'internacion_id' => $intSofiaId,
            'cama_id' => $camaPisoA,
            'fecha_desde' => now()->subDays(1)->format('Y-m-d H:i:s'),
            'tipo_internacion' => 'comun',
            'notas' => 'Cama junto a la ventana. Monitoreo estándar.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 10. Turnos Médicos Agendados
        DB::table('turnos')->where('empresa_id', $empresaDemo->id)->delete();

        // 5 Turnos Completados de Mañana
        for ($h = 8; $h <= 12; $h++) {
            DB::table('turnos')->insert([
                'empresa_id' => $empresaDemo->id,
                'client_id' => $pacienteSofiaId,
                'user_id' => $adminUser->id,
                'fecha' => now()->format('Y-m-d'),
                'hora_inicio' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00:00',
                'hora_fin' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':45:00',
                'estado' => 'completado',
                'notas' => 'Sesión de Fisioterapia y Movilidad',
                'monto' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3 Turnos Pendientes de Tarde
        for ($h = 15; $h <= 17; $h++) {
            DB::table('turnos')->insert([
                'empresa_id' => $empresaDemo->id,
                'client_id' => $pacienteAmbarId,
                'user_id' => $doctorUser->id,
                'fecha' => now()->format('Y-m-d'),
                'hora_inicio' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00:00',
                'hora_fin' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':30:00',
                'estado' => 'pendiente',
                'notas' => 'Consulta de Control Odontológico',
                'monto' => 18000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 11. Finanzas / Movimientos de Tesorería
        DB::table('finanzas_movimientos')->where('empresa_id', $empresaDemo->id)->delete();
        DB::table('finanzas_cuentas')->where('empresa_id', $empresaDemo->id)->delete();

        $cajaCta = DB::table('finanzas_cuentas')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Caja Central Sanatorio',
            'tipo' => 'caja',
            'moneda' => 'ARS',
            'saldo_inicial' => 450000,
            'saldo_actual' => 450000,
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $bancoCta = DB::table('finanzas_cuentas')->insertGetId([
            'empresa_id' => $empresaDemo->id,
            'nombre' => 'Banco Galicia Corriente',
            'tipo' => 'banco',
            'moneda' => 'ARS',
            'saldo_inicial' => 2480000,
            'saldo_actual' => 2480000,
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Registrar movimientos
        DB::table('finanzas_movimientos')->insert([
            [
                'empresa_id' => $empresaDemo->id,
                'cuenta_id' => $cajaCta,
                'user_id' => $adminUser->id,
                'tipo' => 'ingreso',
                'monto' => 60000,
                'fecha' => now()->format('Y-m-d'),
                'concepto' => 'Cobro de co-pagos por fisioterapia - Día de hoy',
                'categoria' => 'Servicios Médicos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'empresa_id' => $empresaDemo->id,
                'cuenta_id' => $bancoCta,
                'user_id' => $adminUser->id,
                'tipo' => 'ingreso',
                'monto' => 125000,
                'fecha' => now()->subDays(1)->format('Y-m-d'),
                'concepto' => 'Liquidación mensual de reintegros de OSDE 310',
                'categoria' => 'Obras Sociales',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'empresa_id' => $empresaDemo->id,
                'cuenta_id' => $cajaCta,
                'user_id' => $adminUser->id,
                'tipo' => 'egreso',
                'monto' => 18000,
                'fecha' => now()->subDays(2)->format('Y-m-d'),
                'concepto' => 'Compra de gasas, jeringas y algodón para stock',
                'categoria' => 'Insumos Clínicos',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        ]);

        // 12. Productos & Insumos Médicos (Farmacia Interna)
        DB::table('products')->where('empresa_id', $empresaDemo->id)->delete();
        
        $uUnidad = Unit::firstOrCreate(['empresa_id' => $empresaDemo->id, 'short_name' => 'unidad'], ['name' => 'Unidades'])->id;
        $rFarmacia = Rubro::firstOrCreate(['empresa_id' => $empresaDemo->id, 'nombre' => 'Farmacia e Insumos'], ['activo' => true])->id;

        Product::create([
            'empresa_id' => $empresaDemo->id,
            'rubro_id' => $rFarmacia,
            'name' => 'Jeringa Descartable 10ml',
            'barcode' => 'MED-JER-01',
            'sku' => 'MED-JER-01',
            'price' => 450,
            'cost' => 180,
            'stock' => 1500,
            'stock_actual' => 1500,
            'stock_min' => 0,
            'stock_ideal' => 0,
            'usage_type' => 'raw_material',
            'is_sellable' => false,
            'unit_id' => $uUnidad,
            'active' => true
        ]);

        Product::create([
            'empresa_id' => $empresaDemo->id,
            'rubro_id' => $rFarmacia,
            'name' => 'Gasas Estériles (Caja x100)',
            'barcode' => 'MED-GAS-02',
            'sku' => 'MED-GAS-02',
            'price' => 2500,
            'cost' => 1100,
            'stock' => 420,
            'stock_actual' => 420,
            'stock_min' => 0,
            'stock_ideal' => 0,
            'usage_type' => 'raw_material',
            'is_sellable' => false,
            'unit_id' => $uUnidad,
            'active' => true
        ]);

        Product::create([
            'empresa_id' => $empresaDemo->id,
            'rubro_id' => $rFarmacia,
            'name' => 'Amoxicilina 500mg (Caja x30)',
            'barcode' => 'MED-AMO-03',
            'sku' => 'MED-AMO-03',
            'price' => 8900,
            'cost' => 3800,
            'stock' => 85,
            'stock_actual' => 85,
            'stock_min' => 0,
            'stock_ideal' => 0,
            'usage_type' => 'sell',
            'is_sellable' => true,
            'unit_id' => $uUnidad,
            'active' => true
        ]);
    }
}
