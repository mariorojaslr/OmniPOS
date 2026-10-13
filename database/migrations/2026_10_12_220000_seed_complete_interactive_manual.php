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
        $articulos = [
            // 1. INICIO / DASHBOARD
            [
                'route_name' => 'empresa.dashboard',
                'title' => 'Inicio y Tablero General de Control',
                'content' => '<p>Bienvenido al <strong>Tablero Principal</strong> de tu clínica o empresa. Desde este centro de comando tienes una vista panorámica en tiempo real de toda tu operación:</p>
                <ul>
                    <li><strong>Métricas Clave:</strong> Visualiza ingresos del día, turnos pendientes de atención, pacientes en espera y estado del stock en segundos.</li>
                    <li><strong>Accesos Rápidos:</strong> Ingresa directo a la agenda de turnos, caja rápida (POS) o a la creación de nuevas consultas médicas.</li>
                    <li><strong>Alertas Inteligentes:</strong> El sistema te notificará sobre pacientes demorados, insumos con stock crítico o pagos pendientes.</li>
                </ul>
                <div class="alert alert-info py-2 px-3 small"><strong>💡 Consejo Pro:</strong> Si utilizas pantalla táctil o tablet, puedes navegar fácilmente tocando sobre las tarjetas de resumen para ir directo al detalle de cada módulo.</div>',
                'video_url' => null,
            ],

            // 2. TURNOS / AGENDA
            [
                'route_name' => 'empresa.turnos.index',
                'title' => 'Agenda de Turnos y Citas Médicas',
                'content' => '<p>Gestiona los horarios, citas y profesionales con la máxima fluidez:</p>
                <ul>
                    <li><strong>Vista Calendario y Lista:</strong> Filtra por profesional, consultorio o especialidad para evitar solapamientos.</li>
                    <li><strong>Nuevo Turno:</strong> Haz clic en cualquier espacio horario disponible o en el botón superior para agendar a un paciente existente o dar de alta uno nuevo en el momento.</li>
                    <li><strong>Estados del Turno:</strong> Cambia el estado a <em>Confirmado</em>, <em>En Espera</em>, <em>Atendido</em> o <em>Cancelado</em> con un solo clic.</li>
                    <li><strong>Recordatorios WhatsApp:</strong> Envía notificaciones de confirmación automáticas a los pacientes antes de su consulta.</li>
                </ul>
                <div class="alert alert-primary py-2 px-3 small"><strong>✨ Sincronización:</strong> Los turnos agendados por los pacientes desde la web pública aparecen aquí automáticamente en tiempo real.</div>',
                'video_url' => null,
            ],

            // 3. RECEPCIÓN / LLAMADOR
            [
                'route_name' => 'empresa.recepcion.index',
                'title' => 'Hub de Recepción, Check-in y Sala de Espera',
                'content' => '<p>El <strong>Hub de Recepción</strong> está diseñado para el personal de entrada y mostrador:</p>
                <ul>
                    <li><strong>Recepción Inmediata (Check-in):</strong> Cuando el paciente llega al consultorio, el recepcionista marca su ingreso, colocándolo inmediatamente en la lista de espera activa.</li>
                    <li><strong>Llamador de Pacientes a TV:</strong> Llama al paciente por su nombre y asigna el consultorio correspondiente. El llamado sonará y se proyectará al instante en el <strong>Monitor de Sala Smart TV</strong>.</li>
                    <li><strong>Control de Tiempos:</strong> Muestra el tiempo transcurrido desde el ingreso del paciente para garantizar una atención puntual y de calidad.</li>
                </ul>',
                'video_url' => null,
            ],

            // 4. HISTORIA CLÍNICA ELECTRÓNICA
            [
                'route_name' => 'empresa.medical_records.index',
                'title' => 'Historias Clínicas Electrónicas (HCE)',
                'content' => '<p>Accede al registro médico digital completo y seguro de cada uno de tus pacientes:</p>
                <ul>
                    <li><strong>Búsqueda Rápida:</strong> Encuentra pacientes por Nombre, Apellido, DNI o Teléfono al instante.</li>
                    <li><strong>Expediente Unificado:</strong> Consulta antecedentes patológicos, alergias, consultas previas, odontogramas históricos y prescripciones farmacológicas.</li>
                    <li><strong>Archivos y Estudios:</strong> Visualiza radiografías, tomografías y análisis de laboratorio almacenados con alta velocidad y seguridad en la nube.</li>
                    <li><strong>Privacidad Garantizada:</strong> Cumple con las normativas de protección de datos de salud y secreto médico profesional.</li>
                </ul>',
                'video_url' => null,
            ],

            // 5. NUEVA CONSULTA / ODONTOGRAMA
            [
                'route_name' => 'empresa.medical_records.create',
                'title' => 'Evolución Clínica y Odontograma Interactivo',
                'content' => '<p>Espacio de trabajo del profesional durante la consulta médica u odontológica:</p>
                <ul>
                    <li><strong>Odontograma Digital FDI:</strong> Trae automáticamente el historial y estado previo del paciente para que no tengas que redibujar nada. Puedes marcar caries, restauraciones, endodoncias, prótesis y extracciones por cara o pieza completa.</li>
                    <li><strong>Diagnóstico y Evolución:</strong> Registra el motivo de consulta, diagnóstico presuntivo/definitivo y plan de tratamiento acordado.</li>
                    <li><strong>Recetas y Certificados:</strong> Genera indicaciones médicas o certificados de reposo con firma y validación por código QR para que el paciente los descargue en su celular.</li>
                </ul>
                <div class="alert alert-success py-2 px-3 small"><strong>💾 Guardado Seguro:</strong> Los cambios se asocian de forma permanente a la historia clínica del paciente.</div>',
                'video_url' => null,
            ],

            // 6. SECTOR IA - PANEL CENTRAL
            [
                'route_name' => 'empresa.ai.index',
                'title' => 'Sector de Inteligencia Artificial & Automatización',
                'content' => '<p>Bienvenido al módulo más avanzado de la plataforma. La Inteligencia Artificial te permite digitalizar y migrar tu clínica sin esfuerzo manual:</p>
                <ul>
                    <li><strong>Carga Masiva de Pacientes:</strong> Sube archivos Excel, CSV o texto desestructurado de software antiguo. La IA limpiará, normalizará y creará los pacientes en segundos.</li>
                    <li><strong>Escaneo de Odontogramas Antiguos:</strong> Fotografía o escanea odontogramas en papel o fichas físicas. La IA detectará las piezas dentales y sus tratamientos para digitalizarlos automáticamente.</li>
                    <li><strong>Asistente Clínico Inteligente:</strong> Herramientas de soporte diagnóstico y resumen de antecedentes para ahorrar horas de trabajo administrativo.</li>
                </ul>',
                'video_url' => null,
            ],

            // 7. CARGA DE PACIENTES CON IA
            [
                'route_name' => 'empresa.ai.import-patients',
                'title' => 'Carga y Normalización de Pacientes con Inteligencia Artificial',
                'content' => '<p>Migra tu base de datos de pacientes desde cualquier software previo o planilla de cálculo:</p>
                <ol>
                    <li><strong>Selecciona tu archivo:</strong> Arrastra tu planilla de Excel (.xlsx, .xls) o archivo .csv sin preocuparte por el orden de las columnas.</li>
                    <li><strong>Interpretación Inteligente:</strong> El motor de IA detecta automáticamente qué columna corresponde al DNI, Nombre, Apellido, Celular, Fecha de Nacimiento y Obra Social.</li>
                    <li><strong>Revisión y Aprobación:</strong> Visualiza una vista previa interactiva donde puedes corregir cualquier dato antes de confirmar la importación definitiva.</li>
                </ol>
                <div class="alert alert-warning py-2 px-3 small"><strong>🔒 Cero Duplicados:</strong> Si un paciente ya existe por DNI o Email, el sistema te consultará si deseas actualizarlo o conservar el registro existente.</div>',
                'video_url' => null,
            ],

            // 8. ESCANEO DE ODONTOGRAMAS CON IA
            [
                'route_name' => 'empresa.ai.scan-odontogram',
                'title' => 'Escaneo y Digitalización IA de Odontogramas Antiguos',
                'content' => '<p>Elimina las carpetas de papel y pasa los odontogramas tradicionales al sistema digital en segundos:</p>
                <ol>
                    <li><strong>Toma la foto o escaneo:</strong> Sube la imagen del odontograma impreso o ficha clínica manual del paciente.</li>
                    <li><strong>Selecciona el Paciente:</strong> Elige a qué paciente se le asignará la ficha digitalizada.</li>
                    <li><strong>Análisis por Visión Artificial:</strong> La IA examina las marcas en lápiz, lapicera o simbología clínica estándar (caries en rojo, arreglos en azul, extracciones con cruces) y genera la matriz digital 2D/3D.</li>
                    <li><strong>Confirmación con un toque:</strong> Valida el odontograma resultante y queda incorporado de por vida a la historia clínica del paciente.</li>
                </ol>',
                'video_url' => null,
            ],

            // 9. CLIENTES / PACIENTES
            [
                'route_name' => 'empresa.clientes.index',
                'title' => 'Cartera de Pacientes y Clientes',
                'content' => '<p>Directorio centralizado de pacientes y clientes de tu establecimiento:</p>
                <ul>
                    <li><strong>Ficha de Paciente 360°:</strong> Datos filiatorios, información de contacto de emergencia, obra social y número de carnet.</li>
                    <li><strong>Historial de Turnos y Asistencias:</strong> Conoce cuántas veces asistió, canceló o reprogramó turnos.</li>
                    <li><strong>Cuenta Corriente y Saldo:</strong> Lleva el control de pagos recibidos, tratamientos financiados y saldos pendientes.</li>
                    <li><strong>Acceso Rápido a HCE:</strong> Botón directo para ingresar a la historia clínica del paciente con un solo clic.</li>
                </ul>',
                'video_url' => null,
            ],

            // 10. SERVICIOS Y ARANCELES
            [
                'route_name' => 'empresa.servicios.index',
                'title' => 'Servicios, Tratamientos y Lista de Aranceles',
                'content' => '<p>Catálogo de prestaciones profesionales y tratamientos disponibles:</p>
                <ul>
                    <li><strong>Configuración de Precios:</strong> Establece el valor particular del tratamiento y los aranceles convenidos por obra social o prepaga.</li>
                    <li><strong>Duración Estimada:</strong> Define cuántos minutos requiere cada práctica para organizar automáticamente los bloques de turnos.</li>
                    <li><strong>Visibilidad Web:</strong> Decide si el tratamiento se publica en la web de reservas online para que los pacientes lo contraten de forma directa.</li>
                </ul>',
                'video_url' => null,
            ],

            // 11. ESPECIALIDADES MÉDICAS
            [
                'route_name' => 'empresa.especialidades.index',
                'title' => 'Especialidades Médicas y Odontológicas',
                'content' => '<p>Organiza las distintas áreas de atención de tu centro:</p>
                <ul>
                    <li><strong>Clasificación:</strong> Crea especialidades como Odontología General, Ortodoncia, Implantología, Pediatría, Dermatología, etc.</li>
                    <li><strong>Asignación a Profesionales:</strong> Vincula a cada médico u odontólogo con una o varias especialidades para facilitar el filtro en la agenda y en la web de turnos.</li>
                </ul>',
                'video_url' => null,
            ],

            // 12. LIQUIDACIONES Y HONORARIOS
            [
                'route_name' => 'empresa.liquidaciones.index',
                'title' => 'Liquidaciones Médicas y Honorarios Profesionales',
                'content' => '<p>Cálculo transparente y automático de honorarios para profesionales del equipo:</p>
                <ul>
                    <li><strong>Porcentajes y Comisiones:</strong> Configura comisiones por porcentaje sobre tratamientos realizados o montos fijos por consulta.</li>
                    <li><strong>Cierre de Período:</strong> Genera resúmenes quincenales o mensuales con el detalle de pacientes atendidos, cobros efectuados y neto a pagar a cada doctor.</li>
                    <li><strong>Comprobante de Pago:</strong> Imprime o exporta en PDF el recibo de liquidación para el profesional.</li>
                </ul>',
                'video_url' => null,
            ],

            // 13. CERTIFICADOS QR
            [
                'route_name' => 'empresa.certificados.index',
                'title' => 'Certificados Médicos con Validación QR Oficial',
                'content' => '<p>Emisión digital de certificados médicos, de reposo, apto físico o constancias de atención:</p>
                <ul>
                    <li><strong>Seguridad Antifraude:</strong> Cada certificado emitido incluye un código QR único y seguro.</li>
                    <li><strong>Verificación Pública:</strong> El empleador, escuela o paciente puede escanear el QR desde cualquier celular y comprobar la autenticidad del documento sin necesidad de iniciar sesión.</li>
                    <li><strong>Firma Digital:</strong> Incluye los datos del profesional, número de matrícula y sello digital de la clínica.</li>
                </ul>',
                'video_url' => null,
            ],

            // 14. TURNOS ONLINE Y SMART TV
            [
                'route_name' => 'empresa.turnos.online-config',
                'title' => 'Portal Web de Turnos y Monitor Smart TV de Sala',
                'content' => '<p>Configura las experiencias digitales de cara al paciente y a tu sala de espera:</p>
                <ul>
                    <li><strong>Web de Autogestión de Citas (24/7):</strong> Comparte el enlace de tu clínica para que los pacientes reserven su cita desde su teléfono a cualquier hora.</li>
                    <li><strong>Monitor de Sala (Turnero TV):</strong> Abre la URL del monitor en cualquier Smart TV o pantalla conectada al navegador de la sala de espera. Emitirá un sonido agradable y mostrará el nombre del paciente y consultorio cuando sea llamado desde la recepción.</li>
                    <li><strong>Personalización de Marca:</strong> La pantalla refleja automáticamente los colores y logotipo de tu empresa.</li>
                </ul>',
                'video_url' => null,
            ],

            // 15. AFILIADOS Y OBRAS SOCIALES
            [
                'route_name' => 'empresa.affiliates.index',
                'title' => 'Planes de Salud, Afiliados y Obras Sociales',
                'content' => '<p>Administración de convenios con prepagas, mutuales o planes propios de cobertura:</p>
                <ul>
                    <li><strong>Catálogo de Obras Sociales:</strong> Registra las instituciones con las que opera tu centro (OSDE, Swiss Medical, PAMI, etc.).</li>
                    <li><strong>Planes Propios (Abonos):</strong> Crea planes de membresía o cobertura interna con cobro recurrente mensual para tus pacientes más fieles.</li>
                    <li><strong>Validación de Cobertura:</strong> Controla el número de credencial y porcentaje de cobertura en cada tratamiento o práctica.</li>
                </ul>',
                'video_url' => null,
            ],

            // 16. INTERNACIÓN - MAPA DE CAMAS
            [
                'route_name' => 'empresa.hospitalization.dashboard',
                'title' => 'Internación Hospitalaria - Mapa de Camas en Vivo',
                'content' => '<p>Control visual e interactivo de las camas y habitaciones de internación:</p>
                <ul>
                    <li><strong>Mapa en Tiempo Real:</strong> Conoce al instante el estado de cada cama (Disponible, Ocupada, En Limpieza o Bloqueada).</li>
                    <li><strong>Ficha de Cama:</strong> Haz clic en cualquier cama ocupada para ver el nombre del paciente ingresado, fecha y hora de admisión, diagnóstico y médico a cargo.</li>
                    <li><strong>Asignación Rápida:</strong> Realiza admisiones o traslados entre salas de forma intuitiva arrastrando o seleccionando el destino.</li>
                </ul>',
                'video_url' => null,
            ],

            // 17. HABITACIONES Y CAMAS
            [
                'route_name' => 'empresa.hospitalization.rooms',
                'title' => 'Gestión de Habitaciones y Salas de Internación',
                'content' => '<p>Configura la infraestructura física de internación de tu clínica:</p>
                <ul>
                    <li><strong>Creación de Salas:</strong> Define sectores (Pabellón General, Terapia Intensiva, Cuidados Intermedios, Maternidad, etc.).</li>
                    <li><strong>Configuración de Camas:</strong> Agrega camas numeradas, especifica equipamiento asociado (oxígeno, monitor multiparamétrico) y arancel por día.</li>
                </ul>',
                'video_url' => null,
            ],

            // 18. HISTORIAL DE INTERNACIONES
            [
                'route_name' => 'empresa.hospitalization.index',
                'title' => 'Historial y Registro Clínico de Internaciones',
                'content' => '<p>Registro histórico y auditoría médica de todos los ingresos hospitalarios:</p>
                <ul>
                    <li><strong>Libro de Ingresos y Egresos:</strong> Consulta pacientes dados de alta, tiempo total de estadía y motivo de egreso (alta médica, traslado, etc.).</li>
                    <li><strong>Filtros por Fecha y Sector:</strong> Exporta informes estadísticos para auditorías de salud y comités médicos.</li>
                </ul>',
                'video_url' => null,
            ],

            // 19. FACTURACIÓN DE INTERNACIONES
            [
                'route_name' => 'empresa.hospitalization.billing',
                'title' => 'Facturación y Liquidación de Internación',
                'content' => '<p>Cálculo de costos asociados a la estadía hospitalaria:</p>
                <ul>
                    <li><strong>Cargos por Días de Cama:</strong> Multiplica automáticamente los días u horas de permanencia según la tarifa configurada.</li>
                    <li><strong>Medicamentos e Insumos Utilizados:</strong> Imputa el consumo de fármacos y descartables provistos por farmacia durante la internación.</li>
                    <li><strong>Generación de Factura / Liquidación:</strong> Emite la cuenta final para el paciente, obra social o compañía aseguradora.</li>
                </ul>',
                'video_url' => null,
            ],

            // 20. ARTÍCULOS E INSUMOS
            [
                'route_name' => 'empresa.products.index',
                'title' => 'Insumos Médicos, Medicamentos y Catálogo',
                'content' => '<p>Catálogo centralizado de productos, insumos descartables y medicamentos:</p>
                <ul>
                    <li><strong>Alta de Artículos:</strong> Registra nombre, código de barras o SKU, categoría, precio de costo y precio de venta.</li>
                    <li><strong>Lotes y Vencimientos:</strong> Habilita el control por número de lote y fecha de caducidad para productos sanitarios.</li>
                    <li><strong>Impresión de Etiquetas:</strong> Genera códigos de barras para etiquetar estanterías o envases de insumos.</li>
                </ul>',
                'video_url' => null,
            ],

            // 21. STOCK E INVENTARIO
            [
                'route_name' => 'empresa.stock.index',
                'title' => 'Control de Inventario y Movimientos de Stock',
                'content' => '<p>Monitorea las cantidades físicas existentes en cada depósito:</p>
                <ul>
                    <li><strong>Existencias en Tiempo Real:</strong> Conoce el stock disponible de cada insumo, apósito, anestesia o instrumental.</li>
                    <li><strong>Ajustes de Inventario:</strong> Registra ingresos manuales, mermas por vencimiento o roturas con justificación auditable.</li>
                    <li><strong>Descuento Automático:</strong> El sistema deduce insumos automáticamente al realizar tratamientos o ventas en mostrador.</li>
                </ul>',
                'video_url' => null,
            ],

            // 22. STOCK FALTANTES
            [
                'route_name' => 'empresa.stock.faltantes',
                'title' => 'Auditoría de Stock Crítico y Reposición',
                'content' => '<p>Detecta con anticipación qué productos están por agotarse para nunca detener la atención:</p>
                <ul>
                    <li><strong>Alerta Temprana:</strong> Lista todos los insumos cuyo stock actual es igual o menor al punto mínimo configurado.</li>
                    <li><strong>Generar Pedido:</strong> Envía una solicitud directa al módulo de compras para reabastecer con tus proveedores habituales.</li>
                </ul>',
                'video_url' => null,
            ],

            // 23. VENTAS
            [
                'route_name' => 'empresa.ventas.index',
                'title' => 'Gestión de Ventas y Comprobantes',
                'content' => '<p>Historial y registro de cobros por servicios profesionales y venta de productos:</p>
                <ul>
                    <li><strong>Registro de Comprobantes:</strong> Consulta recibos, presupuestos y facturas emitidas por fecha, cliente o estado.</li>
                    <li><strong>Múltiples Formas de Pago:</strong> Cobra en Efectivo, Tarjeta, Transferencia Bancaria o billeteras digitales con desglose exacto.</li>
                    <li><strong>Impresión Térmica y PDF:</strong> Imprime tickets en impresoras térmicas de 58mm/80mm o descarga facturas en tamaño A4.</li>
                </ul>',
                'video_url' => null,
            ],

            // 24. POS RÁPIDO
            [
                'route_name' => 'empresa.pos.index',
                'title' => 'Terminal de Punto de Venta (POS) Táctil',
                'content' => '<p>Terminal de facturación ultra rápida optimizada para pantalla táctil o lector de código de barras:</p>
                <ul>
                    <li><strong>Cobro en 3 Clics:</strong> Selecciona el servicio o insumo, elige el medio de pago e imprime el ticket sin demoras.</li>
                    <li><strong>Múltiples Vendedores:</strong> Permite que recepcionistas o cajeros cobren con su usuario identificado.</li>
                    <li><strong>Apertura y Cierre de Caja:</strong> Inicia el turno con saldo inicial y ciérralo al finalizar la jornada con cuadre de billetes.</li>
                </ul>',
                'video_url' => null,
            ],

            // 25. COMPRAS Y ABASTO
            [
                'route_name' => 'empresa.compras.index',
                'title' => 'Abasto, Compras e Ingreso de Mercadería',
                'content' => '<p>Registra las compras realizadas a proveedores y droguerías:</p>
                <ul>
                    <li><strong>Ingreso a Depósito:</strong> La carga de una compra suma automáticamente las cantidades al stock físico.</li>
                    <li><strong>Actualización de Costos:</strong> Modifica automáticamente los costos de compra para mantener los márgenes de ganancia al día.</li>
                    <li><strong>Cuentas por Pagar:</strong> Lleva el saldo adeudado a cada droguería o distribuidor.</li>
                </ul>',
                'video_url' => null,
            ],

            // 26. PROVEEDORES
            [
                'route_name' => 'empresa.proveedores.index',
                'title' => 'Gestión de Proveedores y Droguerías',
                'content' => '<p>Directorio de distribuidores, droguerías y proveedores de servicios:</p>
                <ul>
                    <li><strong>Datos de Contacto:</strong> Razón social, CUIT/RUT, teléfono, correo electrónico y dirección comercial.</li>
                    <li><strong>Historial de Compras:</strong> Conoce qué insumos se le compraron históricamente y los plazos de entrega pactados.</li>
                </ul>',
                'video_url' => null,
            ],

            // 27. TESORERÍA Y FINANZAS
            [
                'route_name' => 'empresa.tesoreria.index',
                'title' => 'Cuentas Bancarias, Billeteras y Finanzas',
                'content' => '<p>Control global de los flujos monetarios de la empresa:</p>
                <ul>
                    <li><strong>Cuentas y Cajas:</strong> Administra cajas chicas en efectivo, cuentas bancarias y cuentas en pasarelas de pago.</li>
                    <li><strong>Transferencias Internas:</strong> Registra movimientos de dinero entre la caja diaria de mostrador y la cuenta bancaria principal.</li>
                    <li><strong>Conciliación:</strong> Mantén la coincidencia exacta entre los saldos del sistema y los extractos bancarios reales.</li>
                </ul>',
                'video_url' => null,
            ],

            // 28. GASTOS OPERATIVOS
            [
                'route_name' => 'empresa.gastos.index',
                'title' => 'Gestión y Clasificación de Gastos Operativos',
                'content' => '<p>Registro y categorización de todos los egresos del consultorio o clínica:</p>
                <ul>
                    <li><strong>Categorías de Gasto:</strong> Agrupa gastos en Alquiler, Servicios (Luz, Agua, Internet), Sueldos, Mantenimiento de Equipos o Impuestos.</li>
                    <li><strong>Impacto en Rentabilidad:</strong> Cada gasto registrado se resta automáticamente de las utilidades en los reportes contables.</li>
                    <li><strong>Adjunto de Facturas:</strong> Sube fotos de tickets o comprobantes para respaldo contable digital.</li>
                </ul>',
                'video_url' => null,
            ],

            // 29. PERSONAL Y EQUIPO
            [
                'route_name' => 'empresa.usuarios.index',
                'title' => 'Gestión de Personal, Médicos y Roles',
                'content' => '<p>Administra los usuarios con acceso al sistema dentro de tu clínica:</p>
                <ul>
                    <li><strong>Roles y Permisos:</strong> Asigna roles específicos (Administrador, Médico, Odontólogo, Recepcionista, Facturador).</li>
                    <li><strong>Seguridad de Datos:</strong> Un recepcionista no puede ver balances financieros ni el médico modificar configuraciones fiscales.</li>
                    <li><strong>Consultorio Asignado:</strong> Define qué consultorio físico utiliza cada profesional habitualmente.</li>
                </ul>',
                'video_url' => null,
            ],

            // 30. ASISTENCIA QR
            [
                'route_name' => 'empresa.personal.asistencia.qr',
                'title' => 'Fichaje de Asistencia y Puntualidad con QR',
                'content' => '<p>Control moderno y sin contacto de la asistencia de tus colaboradores:</p>
                <ul>
                    <li><strong>Reloj Digital QR:</strong> Muestra un código QR dinámico en una tablet o pantalla en la entrada del establecimiento.</li>
                    <li><strong>Fichaje desde el Celular:</strong> Cada empleado escanea el código desde su celular al entrar y salir, registrando fecha, hora y geolocalización.</li>
                    <li><strong>Reportes de Presentismo:</strong> Auditoría clara de horas trabajadas, llegadas tarde y ausencias sin necesidad de equipos biométricos costosos.</li>
                </ul>',
                'video_url' => null,
            ],

            // 31. LOGÍSTICA / GPS
            [
                'route_name' => 'empresa.gps.index',
                'title' => 'Centro de Control Logístico y Rastreo GPS',
                'content' => '<p>Monitoreo en vivo de unidades de traslado, ambulancias o móviles de entrega:</p>
                <ul>
                    <li><strong>Ubicación Satelital:</strong> Conoce en el mapa la posición exacta y velocidad de tus vehículos en tiempo real.</li>
                    <li><strong>Estado de Movilidad:</strong> Detecta si el móvil está en viaje, detenido en un paciente o disponible en base.</li>
                </ul>',
                'video_url' => null,
            ],

            // 32. RUTAS LOGÍSTICAS
            [
                'route_name' => 'empresa.gps.rutas',
                'title' => 'Smart Delivery y Rutas de Despacho',
                'content' => '<p>Optimización de traslados a domicilio y entregas de insumos:</p>
                <ul>
                    <li><strong>Planificación de Paradas:</strong> Agrupa visitas médicas domiciliarias o entregas de prótesis por cercanía geográfica.</li>
                    <li><strong>Notificación al Destinatario:</strong> Envía enlaces de seguimiento para que sepan a qué hora llegará el móvil.</li>
                </ul>',
                'video_url' => null,
            ],

            // 33. REMITOS
            [
                'route_name' => 'empresa.remitos.index',
                'title' => 'Historial de Remitos y Despachos',
                'content' => '<p>Documentación oficial de traslado de mercadería e instrumental médico:</p>
                <ul>
                    <li><strong>Emisión de Remitos:</strong> Genera comprobantes de entrega numerados para constatar la recepción de materiales en laboratorios o sucursales.</li>
                    <li><strong>Firmas de Recepción:</strong> Registra la conformidad de entrega del chofer y destinatario.</li>
                </ul>',
                'video_url' => null,
            ],

            // 34. REPORTES GLOBALES
            [
                'route_name' => 'empresa.reportes.panel',
                'title' => 'Centro de Inteligencia y Estadísticas de Negocio',
                'content' => '<p>Toma de decisiones basada en datos reales de tu clínica:</p>
                <ul>
                    <li><strong>Facturación por Período:</strong> Gráficos comparativos de ventas día a día, mes a mes y año contra año.</li>
                    <li><strong>Tratamientos Más Demandados:</strong> Identifica cuáles son los servicios con mayor rentabilidad y afluencia de pacientes.</li>
                    <li><strong>Rendimiento Profesional:</strong> Cantidad de consultas atendidas y facturación generada por cada médico.</li>
                </ul>',
                'video_url' => null,
            ],

            // 35. ARQUEO DE CAJA
            [
                'route_name' => 'empresa.reportes.caja_diaria',
                'title' => 'Cuadre y Auditoría de Caja Diaria',
                'content' => '<p>Auditoría precisa para cerrar el día con tranquilidad contable:</p>
                <ul>
                    <li><strong>Resumen por Medio de Cobro:</strong> Desglose exacto de cuánto ingresó en Efectivo, Débito, Crédito y Transferencias.</li>
                    <li><strong>Diferencias de Caja:</strong> Compara lo registrado en el sistema contra el arqueo real contado por el cajero.</li>
                </ul>',
                'video_url' => null,
            ],

            // 36. RENTABILIDAD
            [
                'route_name' => 'empresa.reportes.rentabilidad',
                'title' => 'Métricas de Rentabilidad y Margen Neto',
                'content' => '<p>Descubre la ganancia neta real de tu operación:</p>
                <ul>
                    <li><strong>Ingresos vs. Egresos:</strong> Resta el costo de insumos y los gastos fijos para calcular el margen operativo exacto.</li>
                    <li><strong>Punto de Equilibrio:</strong> Conoce cuánto necesita facturar la clínica cada mes para cubrir todos sus costos fijos.</li>
                </ul>',
                'video_url' => null,
            ],

            // 37. AJUSTES Y CONFIGURACIÓN
            [
                'route_name' => 'empresa.configuracion.index',
                'title' => 'Configuración de la Clínica y Branding',
                'content' => '<p>Personaliza todos los aspectos visuales y operativos de tu plataforma:</p>
                <ul>
                    <li><strong>Identidad Visual (Branding):</strong> Sube tu logotipo y elige tus 3 colores corporativos. El sistema adaptará toda la plataforma a tu marca.</li>
                    <li><strong>Datos de la Clínica:</strong> Nombre comercial, dirección, teléfono de WhatsApp para turnos y CUIT.</li>
                    <li><strong>Módulos Activos:</strong> Activa o desactiva módulos según el tipo de servicio que brindes (Odontología, Hospitalización, Ventas, etc.).</li>
                </ul>',
                'video_url' => null,
            ],

            // 38. BACKUPS
            [
                'route_name' => 'empresa.backup.index',
                'title' => 'Bóveda de Backups y Seguridad de Datos',
                'content' => '<p>Protección total de la información de tus pacientes y contabilidad:</p>
                <ul>
                    <li><strong>Copias de Seguridad con un Clic:</strong> Descarga un respaldo completo de tu base de datos en cualquier momento.</li>
                    <li><strong>Respaldo en la Nube:</strong> El sistema genera copias automáticas encriptadas para asegurar la continuidad de tu servicio frente a cualquier contingencia.</li>
                </ul>',
                'video_url' => null,
            ],

            // 39. SOPORTE
            [
                'route_name' => 'empresa.soporte.index',
                'title' => 'Centro de Ayuda y Tickets de Soporte Técnico',
                'content' => '<p>Canal directo de comunicación con nuestro equipo técnico especializado:</p>
                <ul>
                    <li><strong>Generar Ticket:</strong> Describe cualquier consulta o requerimiento. Nuestro equipo responderá en los plazos fijados en tu plan.</li>
                    <li><strong>Seguimiento de Respuestas:</strong> Visualiza el estado de tus solicitudes (Abierto, En Revisión o Resuelto).</li>
                </ul>',
                'video_url' => null,
            ],

            // 40. PANEL OWNER
            [
                'route_name' => 'owner.dashboard',
                'title' => 'Panel de Control Maestro (Nivel Owner)',
                'content' => '<p>Centro de control para la dirección de la plataforma SaaS multiempresa:</p>
                <ul>
                    <li><strong>Visión Omnisciente:</strong> Supervisa todas las clínicas y empresas suscritas en el sistema.</li>
                    <li><strong>Métricas Globales SaaS:</strong> Facturación mensual recurrente (MRR), total de usuarios activos y almacenamiento consumido.</li>
                    <li><strong>Mimetización ("Entrar como"):</strong> Ingresa a cualquier empresa cliente con un solo clic para brindar soporte sin solicitar contraseñas.</li>
                </ul>',
                'video_url' => null,
            ],

            // 41. DIRECTORIO DE EMPRESAS (OWNER)
            [
                'route_name' => 'owner.empresas.index',
                'title' => 'Directorio de Clínicas y Empresas Clientes',
                'content' => '<p>Gestión completa de las cuentas de clientes de la plataforma:</p>
                <ul>
                    <li><strong>Altas y Estados:</strong> Crea nuevas clínicas, suspende cuentas por falta de pago o activa bonificaciones especiales de demostración.</li>
                    <li><strong>Asignación de Planes:</strong> Cambia de plan o define límites de almacenamiento, usuarios y módulos habilitados.</li>
                </ul>',
                'video_url' => null,
            ],

            // 42. PLANES SAAS (OWNER)
            [
                'route_name' => 'owner.planes.index',
                'title' => 'Gestión de Planes y Precios SaaS',
                'content' => '<p>Estructura comercial de la plataforma por niveles de suscripción:</p>
                <ul>
                    <li><strong>Niveles de Servicio:</strong> Define planes (Plan Básico $25.000/mes, Plan Profesional, Plan Clínica Dental, Plan Hospitalario).</li>
                    <li><strong>Límites y Capacidades:</strong> Fija límites de profesionales, almacenamiento en la nube y acceso al sector de Inteligencia Artificial.</li>
                </ul>',
                'video_url' => null,
            ],

            // 43. TELEMETRÍA BUNNY.NET Y ANÁLISIS (OWNER)
            [
                'route_name' => 'owner.analytics.index',
                'title' => 'Telemetría Bunny.net y Métricas de Infraestructura',
                'content' => '<p>Supervisión del consumo de recursos pesados y CDN:</p>
                <ul>
                    <li><strong>Bunny Storage & Stream:</strong> Monitorea gigabytes de archivos de historias clínicas, radiografías y videos consumidos por cada empresa.</li>
                    <li><strong>Tráfico Global:</strong> Conoce el ancho de banda transferido y la latencia para garantizar la máxima velocidad de respuesta a los clientes.</li>
                </ul>',
                'video_url' => null,
            ],
        ];

        foreach ($articulos as $item) {
            HelpArticle::updateOrCreate(
                ['route_name' => $item['route_name']],
                [
                    'title'      => $item['title'],
                    'content'    => $item['content'],
                    'video_url'  => $item['video_url'],
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
        // Se preservan los artículos de ayuda creados
    }
};
