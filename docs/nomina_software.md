# OmniPOS: Nómina e Inventario de Recursos de Software (Medical & SaaS Suite)

Este documento detalla exhaustivamente cada uno de los módulos operativos, utilidades clave, arquitectura, rutas centrales, bases de datos asociadas y archivos core que componen el ecosistema de la plataforma SaaS multiempresa **OmniPOS**.

---

## 🛡️ 1. Arquitectura Central y Multi-tenancy

La plataforma está diseñada sobre **Laravel 12**, **MySQL** y **Bootstrap 5**, implementando una arquitectura de aislamiento absoluto de datos por empresa (Multi-tenancy).

- **Trait de Multi-tenancy**: [BelongsToEmpresa.php](file:///c:/xampp/htdocs/poolhub/app/Traits/BelongsToEmpresa.php)
  - Filtra de manera transparente todas las consultas Eloquent agregando la cláusula `where('empresa_id', auth()->user()->empresa_id)`.
- **Modelos Clave Asociados**:
  - `App\Models\Empresa` (Entidad inquilina)
  - `App\Models\User` (Usuarios bajo jerarquía de roles: Owner, Administrador Central, Administrador Cliente, Profesional, Operador)
  - `App\Models\Servicio` (Prácticas y aranceles médicos)
  - `App\Models\Especialidad` (Especialidades clínicas)
  - `App\Models\ObraSocial` (Convenios de cobertura)

---

## 💼 2. Módulos Operativos y Utilidades Clave

### 🏥 A. Gestión de Consultorios y Atención Primaria (Turnos)
Automatiza el agendamiento y control de pacientes en consultorios médicos.

- **Utilidades**:
  - Agenda visual e interactiva de turnos diarios y semanales.
  - Configuración avanzada de turnos online y pasarela de pago.
- **Rutas Core**:
  - `empresa/turnos` (`empresa.turnos.index`)
  - `empresa/turnos-config` (`empresa.turnos.online-config`)
- **Archivos Clave**:
  - Controlador: [TurnoController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/TurnoController.php)
  - Vista: [turnos/index.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/turnos/index.blade.php)

---

### 🛰️ B. Hub de Recepción & Cola de Espera (Sala de Espera)
Panel de control táctico y en tiempo real para la recepción del sanatorio.

- **Utilidades**:
  - Búsqueda veloz de pacientes con check-in en un clic.
  - Registro exacto de hora de arribo (`hora_arribo`) y estado en la cola de espera (`estado_cola`).
  - Asignación dinámica de médicos activos a consultorios físicos (`consultorio`).
- **Rutas Core**:
  - `empresa/recepcion` (`empresa.recepcion.index`)
  - `empresa/api/recepcion/arribo/{id}` (`empresa.recepcion.arribo`)
- **Archivos Clave**:
  - Controlador: [ReceptionistController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/ReceptionistController.php)
  - Vista: [recepcion/index.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/recepcion/index.blade.php)

---

### 🩺 C. Historia Clínica Electrónica (HCE) y Estudios Clínicos
Registro confidencial y digital de la evolución clínica del paciente.

- **Utilidades**:
  - Ficha médica unificada, antecedentes, alergias e historial de consultas.
  - Subida directa de estudios clínicos complementarios (imágenes, PDFs de laboratorios) a almacenamiento en la nube (**Bunny Storage**).
- **Rutas Core**:
  - `empresa/medical_records` (`empresa.medical_records.index`)
  - `empresa/clients/{client}/medical-history` (`empresa.medical_records.patient_history`)
- **Archivos Clave**:
  - Controlador: [MedicalRecordController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/MedicalRecordController.php)
  - Vista: [medical_records/index.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/medical_records/index.blade.php)

---

### 📑 D. Especialidades Clínicas (ABM / CRUD)
Gestión unificada de las áreas médicas habilitadas en la clínica.

- **Utilidades**:
  - CRUD premium OLED/Light de especialidades clínicas.
  - Cálculo dinámico en tiempo real de la cantidad de prestaciones médicas y profesionales asignados a cada especialidad.
- **Rutas Core**:
  - `empresa/especialidades` (`empresa.especialidades.index`)
- **Archivos Clave**:
  - Modelo: [Especialidad.php](file:///c:/xampp/htdocs/poolhub/app/Models/Especialidad.php)
  - Controlador: [EspecialidadController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/EspecialidadController.php)
  - Vista: [especialidades/index.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/especialidades/index.blade.php)

---

### 💳 E. Obras Sociales e Integrador API (Simulador)
Administración de convenios médicos y validación automatizada de credenciales.

- **Utilidades**:
  - Generación de `api_token` único por convenio para integración externa de Obras Sociales.
  - **Simulador API Homologador**: Interfaz interactiva para probar validación de elegibilidad en vivo mediante AJAX con estados enriquecidos.
- **Rutas Core**:
  - `empresa/obras_sociales` (`empresa.obras_sociales.index`)
  - `empresa/obras_sociales/simulate` (`empresa.obras_sociales.simulate`)
- **Archivos Clave**:
  - Controlador: [ObraSocialController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/ObraSocialController.php)
  - Vista: [obras_sociales/index.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/obras_sociales/index.blade.php)

---

### 💰 F. Rendición de Cuentas y Reportes Clínicos
Centro estratégico de auditoría para la administración de la clínica.

- **Utilidades**:
  - KPIs maestros: Facturación Bruta, Honorarios a Liquidar, Margen Neto de Clínica, Deudas de Obras Sociales.
  - Gráfico interactivo Chart.js de evolución mensual.
  - Desglose detallado por profesional médico y aranceles a cobrar por Obra Social.
- **Rutas Core**:
  - `empresa/reportes/rendicion-medica` (`empresa.reportes.rendicion_medica`)
  - `empresa/reportes` (`empresa.reportes.panel`)
- **Archivos Clave**:
  - Controlador: [ReporteController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/ReporteController.php)
  - Vista: [reportes/rendicion_medica.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/reportes/rendicion_medica.blade.php)

---

### 🛏️ G. Internación, Habitaciones y Camas (QR Hospital)
Administración de recursos de internación y hotelería sanatorial.

- **Utilidades**:
  - ABM de salas y habitaciones.
  - Registro de camas físicas, estado (libre, ocupada, mantenimiento).
  - Generación de códigos QR únicos por cama para acceso rápido a enfermería.
- **Rutas Core**:
  - `empresa/hospitalization/rooms` (`empresa.hospitalization.rooms`)
  - `empresa/hospitalization/beds/{bed}/qr` (`empresa.hospitalization.beds.qr`)
- **Archivos Clave**:
  - Controlador: [RoomController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/RoomController.php)
  - Vista: [hospitalization/rooms.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/hospitalization/rooms.blade.php)

---

### 🛒 H. Punto de Venta (POS) y Facturación AFIP
Facturación comercial de medicamentos, insumos farmacéuticos y servicios de caja.

- **Utilidades**:
  - Pantalla interactiva POS de carga rápida.
  - Factura electrónica fiscal AFIP (Homologación de CUIT).
  - Listado de cheques de terceros, cuentas corrientes y arqueo de caja diario.
- **Rutas Core**:
  - `empresa/pos` (`empresa.pos.index`)
  - `empresa/reportes/caja-diaria` (`empresa.reportes.caja_diaria`)
- **Archivos Clave**:
  - Controlador: [PosController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Empresa/PosController.php)
  - Vista: [pos/index.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/empresa/pos/index.blade.php)

---

### 👑 I. Central Owner Dashboard (SaaS Control)
Panel de control para el administrador supremo (Owner / Mario Rojas) para reventa y monitoreo.

- **Utilidades**:
  - Monitor global OLED de inquilinos, consumo de almacenamiento y tráfico de Bunny.net, suscripciones y facturas.
  - **Función Mimetizar (Entrar como Usuario)**: Permite al Owner entrar temporalmente a cualquier empresa cliente en modo omnisciente para asistencia técnica directa sin requerir contraseñas.
- **Rutas Core**:
  - `owner/dashboard` (`owner.dashboard`)
  - `owner/empresas` (`owner.empresas.index`)
- **Archivos Clave**:
  - Controlador: [OwnerDashboardController.php](file:///c:/xampp/htdocs/poolhub/app/Http/Controllers/Owner/OwnerDashboardController.php)
  - Vista: [owner/dashboard.blade.php](file:///c:/xampp/htdocs/poolhub/resources/views/owner/dashboard.blade.php)

---

## 📈 3. Estado de Madurez de Recursos

| Módulo / Recurso | Estado Operativo | Integración Bunny.net | UI/UX Style |
| :--- | :--- | :---: | :--- |
| **SaaS Owner Dashboard** | 🟢 100% Producción | N/A | OLED Dark Premium |
| **Agenda & Turnos** | 🟢 100% Producción | N/A | Institucional Claro / OLED |
| **Hub Cola de Espera** | 🟢 100% Producción | N/A | Clean Waiting List |
| **Historia Clínica (HCE)** | 🟢 100% Producción | 📁 Bunny Storage | Sleek Modern |
| **Especialidades CRUD** | 🟢 100% Producción | N/A | Sleek Light/OLED Compatible |
| **Simulador API Obras Sociales** | 🟢 100% Producción | N/A | Terminal Glassmorphism OLED |
| **Rendición de Cuentas Médica** | 🟢 100% Producción | N/A | Business Dashboard / ChartJS |
| **QR Camas Internación** | 🟢 100% Producción | N/A | Hospital Suite / QR Engine |
| **Punto de Venta POS** | 🟢 100% Producción | N/A | High Contrast POS Grid |
