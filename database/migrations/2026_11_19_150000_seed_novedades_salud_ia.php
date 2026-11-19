<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SystemUpdate;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $novedades = [
            [
                'title'        => '🩺 Arquitectura Dual: Clínica Médica General + Odontología FDI',
                'description'  => 'Se habilita la convivencia simultánea y switchable entre atención de Medicina General y Odontología Integral en la misma plataforma. Incluye anamnesis clínica, toma de signos vitales, control de glucemia, prescripciones digitales y el Odontograma Interactivo FDI con historial acumulado automático.',
                'image'        => '/images/logo_omnipos.png',
                'type'         => 'Clínica & Salud',
                'publish_date' => '2026-11-19',
                'link_tutorial'=> null,
            ],
            [
                'title'        => '🤖 Asistente Clínico Inteligente: Análisis de Signos Vitales y Alertas Cromáticas',
                'description'  => 'La Historia Clínica Electrónica ahora integra un asistente de Inteligencia Artificial en tiempo real. Al registrar signos vitales (presión arterial sistólica/diastólica, glucemia, frecuencia cardíaca), el sistema los resalta cromáticamente con insignias de alerta (Normal, Precaución, Alerta Crítica) y permite al médico solicitar una valoración clínica confidencial en un clic.',
                'image'        => '/images/logo_omnipos.png',
                'type'         => 'Inteligencia Artificial',
                'publish_date' => '2026-11-19',
                'link_tutorial'=> null,
            ],
            [
                'title'        => '📑 Digitalización Masiva con IA: Escaneo de Odontogramas e Ingesta de Padrones',
                'description'  => 'Lanzamiento oficial del Sector de Inteligencia Artificial para digitalizar fichas antiguas en papel. La visión artificial vectoriza marcas manuales en odontogramas (caries, endodoncias, extracciones) e interpreta padrones desordenados en planillas Excel/CSV para migraciones institucionales en minutos.',
                'image'        => '/images/logo_omnipos.png',
                'type'         => 'Inteligencia Artificial',
                'publish_date' => '2026-11-18',
                'link_tutorial'=> null,
            ],
            [
                'title'        => '📺 Monitor de Sala Smart TV y Turnero Audiovisual en Vivo',
                'description'  => 'Pantalla pública turnera adaptada para Smart TV en salas de espera. Se enlaza de forma bidireccional con el Hub de Recepción para llamar pacientes con sonido institucional y proyección del consultorio asignado en tiempo real.',
                'image'        => '/images/logo_omnipos.png',
                'type'         => 'Recepción & Espera',
                'publish_date' => '2026-11-17',
                'link_tutorial'=> null,
            ],
            [
                'title'        => '📜 Certificados Médicos Oficiales con Validación QR Antifraude',
                'description'  => 'Emisión de certificados médicos de reposo, apto físico y constancias laborales con código QR inviolable. Empleadores o instituciones educativas pueden escanear el documento para verificar autenticidad en tiempo real sin requerir acceso al sistema.',
                'image'        => '/images/logo_omnipos.png',
                'type'         => 'Documentación Médica',
                'publish_date' => '2026-11-16',
                'link_tutorial'=> null,
            ],
        ];

        foreach ($novedades as $nov) {
            SystemUpdate::updateOrCreate(
                ['title' => $nov['title']],
                $nov
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
