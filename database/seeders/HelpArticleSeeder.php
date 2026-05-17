<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HelpArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Manual para Historia Clínica (HCE)
        \App\Models\HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.medical_records.create'],
            [
                'title' => 'Guía: Registro de Evolución Médica',
                'content' => '
                    <p>Bienvenido al módulo de Historia Clínica Electrónica. Aquí registrarás la atención de tus pacientes.</p>
                    <h5 class="fw-bold mt-3 text-primary small">1. Datos Especializados (JSON)</h5>
                    <p>Este es un "cajón inteligente" para guardar datos técnicos de tu especialidad. Debes usar el formato <code>{"clave": "valor"}</code>.</p>
                    <div class="bg-light p-2 rounded mb-3 font-monospace x-small border">
                        Ejemplo Odontología:<br>
                        {"caries": [14, 15], "tratamiento": "limpieza"}
                    </div>
                    <h5 class="fw-bold mt-3 text-primary small">2. Archivos Adjuntos</h5>
                    <p>Puedes subir múltiples archivos (PDF, JPG, PNG). Se guardarán de forma segura en la nube (Bunny.net) para no ocupar espacio en tu servidor local.</p>
                ',
                'is_active' => true
            ]
        );

        // 2. Manual para Gestión de Afiliados
        \App\Models\HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.affiliates.create'],
            [
                'title' => 'Guía: Alta de Nuevo Afiliado',
                'content' => '
                    <p>Sigue estos pasos para registrar correctamente a un paciente en el Plan MedPlus.</p>
                    <ul>
                        <li><strong>Foto de Perfil:</strong> Usa una foto clara. Se sincroniza con el almacenamiento externo Bunny.net automáticamente.</li>
                        <li><strong>Plan y Cobertura:</strong> Selecciona el plan correspondiente para que el sistema calcule la cuota mensual.</li>
                        <li><strong>Obra Social:</strong> Si el paciente tiene una cobertura adicional (ej: APOS), regístrala aquí con su número de carnet.</li>
                    </ul>
                ',
                'is_active' => true
            ]
        );

        // 3. Manual para Configuración de Planes
        \App\Models\HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.affiliate_plans.index'],
            [
                'title' => 'Guía: Configuración de Planes y Prestaciones',
                'content' => '
                    <p>Los planes definen qué beneficios recibe el afiliado y cuánto debe pagar mensualmente.</p>
                    <h5 class="fw-bold mt-3 text-primary small">Definición de Prestaciones</h5>
                    <p>Haz clic en "Agregar" para detallar cada beneficio incluido (ej: "Consultas 100% bonificadas"). Estos beneficios se mostrarán en la ficha del paciente para consulta rápida de recepción.</p>
                ',
                'is_active' => true
            ]
        );
    }
}
