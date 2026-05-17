<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Bed;
use App\Models\Client;
use App\Models\Empresa;
use App\Models\EmpresaConfig;
use App\Models\Hospitalization;
use App\Models\HospitalizationBed;
use App\Models\HospitalizationAdministration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

/**
 * Seeder HospitalizationSeeder
 * Genera datos de infraestructura y de internación clínica realistas para demostración del módulo.
 */
class HospitalizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener primera empresa disponible
        $empresa = Empresa::first();
        if (!$empresa) {
            return;
        }

        // Habilitar el módulo en la configuración de la empresa
        $config = EmpresaConfig::updateOrCreate(
            ['empresa_id' => $empresa->id],
            ['mod_internaciones' => true]
        );

        // 2. Crear Habitaciones de prueba
        $roomUtis = Room::updateOrCreate(
            ['empresa_id' => $empresa->id, 'nombre' => 'Terapia Intensiva (UTI)'],
            ['tipo' => 'terapia_intensiva', 'activo' => true]
        );

        $roomComun = Room::updateOrCreate(
            ['empresa_id' => $empresa->id, 'nombre' => 'Habitación 101 (Común)'],
            ['tipo' => 'comun', 'activo' => true]
        );

        $roomQuirofano = Room::updateOrCreate(
            ['empresa_id' => $empresa->id, 'nombre' => 'Quirófano Central'],
            ['tipo' => 'quirofano', 'activo' => true]
        );

        // 3. Crear Camas de prueba
        $bedUti1 = Bed::updateOrCreate(
            ['empresa_id' => $empresa->id, 'habitacion_id' => $roomUtis->id, 'nombre' => 'Cama UTI-1'],
            ['estado' => 'libre']
        );

        $bedComunA = Bed::updateOrCreate(
            ['empresa_id' => $empresa->id, 'habitacion_id' => $roomComun->id, 'nombre' => 'Cama 101-A'],
            ['estado' => 'libre']
        );

        $bedComunB = Bed::updateOrCreate(
            ['empresa_id' => $empresa->id, 'habitacion_id' => $roomComun->id, 'nombre' => 'Cama 101-B'],
            ['estado' => 'libre']
        );

        $bedQuirofano = Bed::updateOrCreate(
            ['empresa_id' => $empresa->id, 'habitacion_id' => $roomQuirofano->id, 'nombre' => 'Mesa Quirófano 1'],
            ['estado' => 'libre']
        );

        // 4. Crear un Paciente de Prueba (Cliente) si no hay ninguno
        $patient = Client::where('empresa_id', $empresa->id)->first();
        if (!$patient) {
            $patient = Client::create([
                'empresa_id' => $empresa->id,
                'nombre' => 'Carlos Alberto Gómez',
                'cuit_cuil' => '20-30440550-9',
                'telefono' => '11-4567-8901',
                'activo' => true,
            ]);
        }

        // Obtener un usuario médico/profesional
        $user = User::where('empresa_id', $empresa->id)->first() ?? User::first();
        $userId = $user ? $user->id : 1;

        // 5. Crear una internación activa de prueba
        // Si ya hay internaciones activas, no duplicar
        $existsActive = Hospitalization::where('empresa_id', $empresa->id)->where('estado', 'activo')->exists();
        if (!$existsActive && $patient) {
            // Internar al paciente Carlos Alberto Gómez en la cama UTI-1
            $h = Hospitalization::create([
                'empresa_id' => $empresa->id,
                'client_id' => $patient->id,
                'fecha_ingreso' => now()->subDays(3),
                'estado' => 'activo',
                'motivo_ingreso' => 'Paciente ingresa por insuficiencia respiratoria aguda y neumonía bacteriana bilateral.',
                'diagnostico_ingreso' => 'Neumonía bacteriana, no clasificada en otra parte (CIE-10: J15.9)',
            ]);

            // Actualizar estado de la cama a Ocupada
            $bedUti1->estado = 'ocupada';
            $bedUti1->save();

            // Registrar asignación de cama
            HospitalizationBed::create([
                'internacion_id' => $h->id,
                'cama_id' => $bedUti1->id,
                'fecha_desde' => now()->subDays(3),
                'tipo_internacion' => 'terapia_intensiva',
                'notas' => 'Ingreso de urgencia directo de guardia.',
            ]);

            // Cargar controles clínicos históricos de evolución
            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'control_signos',
                'nombre' => 'Control de Signos Vitales - Guardia',
                'cantidad' => 1,
                'fecha_registro' => now()->subDays(3)->addHours(2),
                'notas' => "Temperatura: 38.9 °C\nPresión Arterial: 140/90 mmHg\nFrecuencia Cardíaca: 105 lpm\nSaturación O2: 90% con máscara de reservorio.",
            ]);

            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'control_signos',
                'nombre' => 'Control Clínico - UTI Evolutivo',
                'cantidad' => 1,
                'fecha_registro' => now()->subDays(2),
                'notas' => "Temperatura: 37.2 °C\nPresión Arterial: 120/75 mmHg\nFrecuencia Cardíaca: 82 lpm\nSaturación O2: 96% con cánula nasal de alto flujo.\nPaciente lúcido, reactivo, refiere mejoría sintomática.",
            ]);

            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'control_signos',
                'nombre' => 'Monitoreo Matutino Diario',
                'cantidad' => 1,
                'fecha_registro' => now()->subDay(),
                'notas' => "Temperatura: 36.6 °C\nPresión Arterial: 118/80 mmHg\nFrecuencia Cardíaca: 74 lpm\nSaturación O2: 98% aire ambiente.\nSe suspende oxigenoterapia. Buena mecánica ventilatoria.",
            ]);

            // Cargar medicamentos aplicados
            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'medicamento',
                'nombre' => 'Ceftriaxona 2g',
                'cantidad' => 1,
                'dosis' => '2g',
                'frecuencia' => 'Cada 24 horas',
                'via' => 'Endovenosa',
                'fecha_registro' => now()->subDays(3),
                'proxima_aplicacion' => now()->subDays(2),
                'notas' => 'Primera dosis antibiótica de carga.',
            ]);

            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'medicamento',
                'nombre' => 'Paracetamol 1g Ampolla',
                'cantidad' => 1,
                'dosis' => '1 ampolla',
                'frecuencia' => 'Rescate por fiebre > 38°C',
                'via' => 'Endovenosa',
                'fecha_registro' => now()->subDays(3)->addHour(),
                'notas' => 'Administrado por temperatura de 38.9°C.',
            ]);

            // Cargar consumos descartables realizados
            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'descartable',
                'nombre' => 'Guías de suero microgotero',
                'cantidad' => 2,
                'precio_unitario' => 450.00,
                'fecha_registro' => now()->subDays(3),
            ]);

            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'descartable',
                'nombre' => 'Cánula nasal de oxígeno',
                'cantidad' => 1,
                'precio_unitario' => 850.00,
                'fecha_registro' => now()->subDays(3),
            ]);

            HospitalizationAdministration::create([
                'internacion_id' => $h->id,
                'user_id' => $userId,
                'tipo' => 'descartable',
                'nombre' => 'Jeringas descartables 10cc',
                'cantidad' => 5,
                'precio_unitario' => 120.00,
                'fecha_registro' => now()->subDays(2),
            ]);
        }
    }
}
