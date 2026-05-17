<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HelpArticle;

class HelpArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Manual para Creación de Historia Clínica (HCE - Nueva Consulta)
        HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.medical_records.create'],
            [
                'title' => 'Guía: Nueva Consulta & Atributos Personalizados (Tags)',
                'content' => '
                    <p class="small">Bienvenido al módulo de Historia Clínica Electrónica (HCE). Esta pantalla permite registrar la consulta médica del paciente.</p>
                    
                    <div class="alert alert-info border-0 rounded-3 p-3 mb-3 small" style="background: rgba(13, 110, 253, 0.05);">
                        <h6 class="fw-bold text-primary mb-1"><i class="bi bi-info-circle-fill me-1"></i> ¿Qué son los Campos Dinámicos / Tags HCE?</h6>
                        Son etiquetas personalizadas del tipo <strong>Propiedad: Valor</strong> que te permiten guardar datos clínicos específicos que no están parametrizados en el formulario estándar. ¡Esto otorga flexibilidad absoluta a cualquier especialidad médica!
                    </div>

                    <h6 class="fw-bold mt-3 text-dark small"><i class="bi bi-check-circle-fill text-success me-1"></i> Ejemplos de uso por Especialidad:</h6>
                    <ul class="small ps-3 text-secondary">
                        <li><strong>Odontología:</strong> Propiedad: <code>Pieza Dentaria</code> | Valor: <code>14 con caries de grado 2</code></li>
                        <li><strong>Oftalmología:</strong> Propiedad: <code>Ojo Izquierdo</code> | Valor: <code>Miopía -1.50, Astigmatismo -0.50</code></li>
                        <li><strong>Kinesiología:</strong> Propiedad: <code>Escala Dolor Eva</code> | Valor: <code>8/10 en zona lumbar</code></li>
                        <li><strong>Cardiología:</strong> Propiedad: <code>Riesgo Quirúrgico</code> | Valor: <code>Clase II (Moderado)</code></li>
                    </ul>

                    <h6 class="fw-bold mt-3 text-dark small"><i class="bi bi-braces text-primary me-1"></i> Formato de Intercambio (JSON Técnico)</h6>
                    <p class="small text-secondary">Cuando agregas estos campos, el motor de la plataforma los serializa y los guarda en un bloque JSON puro dentro de la base de datos de forma estructurada:</p>
                    <div class="bg-dark text-success p-3 rounded-3 font-monospace x-small border mb-3" style="font-size: 0.75rem; background-color: #0c0f16 !important; color: #10b981 !important;">
                        {<br>
                        &nbsp;&nbsp;"dynamic_fields": {<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;"Pieza Dentaria": "14 con caries",<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;"Nivel de Dolor": "8/10"<br>
                        &nbsp;&nbsp;}<br>
                        }
                    </div>
                ',
                'is_active' => true
            ]
        );

        // 2. Manual para Edición de Historia Clínica (HCE - Editar Consulta)
        HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.medical_records.edit'],
            [
                'title' => 'Guía: Edición de Consulta & Rectificación de Tags',
                'content' => '
                    <p class="small">En esta pantalla puedes modificar o agregar nuevos campos estructurados a una evolución clínica previamente registrada.</p>
                    
                    <div class="alert alert-warning border-0 rounded-3 p-3 mb-3 small" style="background: rgba(245, 158, 11, 0.05);">
                        <h6 class="fw-bold text-warning mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Importante al Editar</h6>
                        Puedes agregar nuevos campos dinámicos o presionar el icono <code>x</code> sobre las etiquetas ya cargadas para eliminarlas y corregirlas. Al guardar los cambios, el bloque JSON se volverá a regenerar de forma segura en la base de datos.
                    </div>
                ',
                'is_active' => true
            ]
        );

        // 3. Manual para Historial Clínico (HCE - Línea de Tiempo del Paciente)
        HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.medical_records.patient_history'],
            [
                'title' => 'Guía: Expediente Clínico Completo y Visor JSON',
                'content' => '
                    <p class="small">Estás visualizando la línea de tiempo histórica del paciente. Cada evolución muestra de forma premium su diagnóstico, tratamientos, signos vitales y los adjuntos almacenados en Bunny.net.</p>
                    
                    <h6 class="fw-bold mt-3 text-dark small"><i class="bi bi-braces text-primary me-1"></i> El Valor de los Datos Estructurados (JSON)</h6>
                    <p class="small text-secondary">Al final de cada tarjeta de consulta verás el botón <code>Ver JSON Clínico Puro</code>. Esta funcionalidad representa una de las mayores ventajas técnicas de la plataforma:</p>
                    <ul class="small ps-3 text-secondary">
                        <li><strong>Interoperabilidad:</strong> Puedes copiar el bloque JSON con un solo clic y enviarlo a otro sistema médico o base de datos externa de forma limpia.</li>
                        <li><strong>Auditoría:</strong> Contiene el registro íntegro en tiempo real tal como fue almacenado, sin filtros de diseño.</li>
                    </ul>
                ',
                'is_active' => true
            ]
        );

        // 4. Manual para Internaciones (Dossier General)
        HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.hospitalization.dossier'],
            [
                'title' => 'Guía: Dossier de Internación & Expediente Clínico de Habitación',
                'content' => '
                    <p class="small">El Dossier Clínico de Internación reúne toda la información médica del paciente durante su estadía en la cama de la clínica.</p>
                    
                    <h6 class="fw-bold mt-3 text-dark small"><i class="bi bi-tags-fill text-success me-1"></i> Registro de Controles con Tags Dinámicos</h6>
                    <p class="small text-secondary">Cuando registras una visita médica diaria o control de enfermería, cuentas con la pestaña <strong>Tags</strong> para registrar variables no estándar del paciente encamado (ej: <code>Disnea: Leve</code>, <code>Drenaje: 150cc seroso</code>, <code>Vía periférica: Permeable en brazo izquierdo</code>).</p>
                    
                    <div class="alert alert-info border-0 rounded-3 p-3 mb-3 small" style="background: rgba(13, 110, 253, 0.05);">
                        <h6 class="fw-bold text-primary mb-1"><i class="bi bi-magic me-1"></i> Ejemplos Clínicos en Internación:</h6>
                        <ul class="mb-0 ps-3">
                            <li><strong>Propiedad:</strong> <code>Drenaje Abdominal</code> | <strong>Valor:</strong> <code>120ml hemático</code></li>
                            <li><strong>Propiedad:</strong> <code>Oxigenoterapia</code> | <strong>Valor:</strong> <code>Cánula nasal a 2 lpm</code></li>
                            <li><strong>Propiedad:</strong> <code>Sonda Vesical</code> | <strong>Valor:</strong> <code>Permeable, diuresis clara</code></li>
                        </ul>
                    </div>
                ',
                'is_active' => true
            ]
        );

        // 5. Manual para Internaciones - Pestaña Evolución
        HelpArticle::updateOrCreate(
            ['route_name' => 'empresa.hospitalization.dossier__evolucion'],
            [
                'title' => 'Guía: Controles Diarios, Signos y Estructura JSON',
                'content' => '
                    <p class="small">Registra controles médicos diarios, monitoreo de signos vitales (temperatura, saturación, frecuencia) y asócialos a etiquetas personalizadas (Tags).</p>
                    
                    <h6 class="fw-bold mt-3 text-dark small"><i class="bi bi-code-slash text-danger me-1"></i> Ver JSON Técnico de Control</h6>
                    <p class="small text-secondary">Cada control registrado en la línea de tiempo incluye un botón <code>Ver JSON Técnico</code>. Al presionarlo, podrás previsualizar la estructura JSON limpia que agrupa la evolución escrita, los signos vitales numéricos, el laboratorio y los tags dinámicos cargados, ideal para exportar o adjuntar a resúmenes de epicrisis externos.</p>
                ',
                'is_active' => true
            ]
        );
    }
}
