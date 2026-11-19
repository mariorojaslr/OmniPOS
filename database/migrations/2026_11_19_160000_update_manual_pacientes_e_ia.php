<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\HelpArticle;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $nuevosManuales = [
            // ALTA DE NUEVOS PACIENTES / CLIENTES
            [
                'route_name' => 'empresa.clientes.create',
                'title' => 'Alta y Registro de Nuevo Paciente / Afiliado',
                'content' => '<p>Guía para el registro formal de un nuevo paciente o afiliado en la plataforma:</p>
                <ol>
                    <li><strong>Datos Filiatorios Obligatorios:</strong> Ingrese Apellido y Nombre completos, y número de Documento (DNI/Pasaporte) sin puntos. El DNI actuará como identificador único para evitar expedientes duplicados.</li>
                    <li><strong>Vía de Contacto (WhatsApp):</strong> Registre el número de teléfono celular con código de área (ej: 1123456789) para que el paciente reciba automáticamente las confirmaciones de turnos y recordatorios.</li>
                    <li><strong>Cobertura y Obra Social:</strong> Si la clínica opera con convenios (OSDE, Swiss Medical, Camioneros, etc.), seleccione la prestadora y tipee el número de carnet o credencial.</li>
                    <li><strong>Guardado y Apertura de HCE:</strong> Al presionar <em>"Guardar Paciente"</em>, el sistema crea automáticamente su Historia Clínica Electrónica (HCE), dejándolo habilitado para agendar citas presenciales, turnos web y registrar odontogramas.</li>
                </ol>
                <div class="alert alert-info py-2 px-3 small"><strong>💡 Tip Rápido:</strong> Si cuenta con una planilla previa de Excel con decenas de afiliados, puede ingresar a <strong>Sector IA &gt; Carga de Pacientes con IA</strong> para importarlos todos juntos en segundos.</div>',
                'video_url' => null,
            ],

            // NUEVA ATENCIÓN / ASISTENTE IA CLÍNICO
            [
                'route_name' => 'empresa.medical_records.create',
                'title' => 'Evolución Médica, Odontograma FDI y Asistente IA',
                'content' => '<p>Espacio de trabajo para el médico u odontólogo durante la consulta:</p>
                <ul>
                    <li><strong>Selección de Paciente:</strong> Elija al paciente del desplegable. El sistema cargará automáticamente su historial consolidado.</li>
                    <li><strong>Arquitectura Dual (Clínica vs Odontología):</strong> 
                        <ul>
                            <li>Si atiende <em>Odontología</em>: Se activa la pestaña <strong>🦷 Odontograma Clínico</strong>, arrastrando el estado dental previo para registrar novedades sobre las caras de las piezas.</li>
                            <li>Si atiende <em>Clínica Médica</em>: Se utiliza la pestaña <strong>❤️ Signos Vitales</strong> y prescripción farmacológica.</li>
                        </ul>
                    </li>
                    <li><strong>Semáforo de Alertas Cromáticas:</strong> Los valores de presión arterial, glucosa y pulsaciones se analizan en tiempo real mostrando etiquetas de color (Verde = Normal, Amarillo = Precaución, Rojo = Crisis o Alerta Crítica).</li>
                    <li><strong>Interconsulta Privada con IA:</strong> En el cuadro inferior, el profesional puede redactar una consulta confidencial a la IA médica para recibir diagnósticos diferenciales, interacciones de medicamentos o pautas terapéuticas orientativas.</li>
                </ul>',
                'video_url' => null,
            ],
        ];

        foreach ($nuevosManuales as $art) {
            HelpArticle::updateOrCreate(
                ['route_name' => $art['route_name']],
                [
                    'title'      => $art['title'],
                    'content'    => $art['content'],
                    'video_url'  => $art['video_url'],
                    'is_active'  => true,
                ]
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
