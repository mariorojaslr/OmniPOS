<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Aquí se definen comandos programados del sistema.
| Se ejecutan automáticamente mediante el scheduler de Laravel.
|
| Tareas actuales:
| - Auditoría completa del sistema
| - Sugerencias automáticas de reposición de stock
|
| Horario elegido: 05:00 AM
| Motivo: los negocios como heladerías cierran cerca de las 03:00,
| por lo que a las 05:00 el servidor debería estar libre.
|
*/


/*
|--------------------------------------------------------------------------
| COMANDO DE PRUEBA DE LARAVEL
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/*
|--------------------------------------------------------------------------
| PROGRAMADOR DE TAREAS AUTOMÁTICAS
|--------------------------------------------------------------------------
|
| Estas tareas se ejecutan automáticamente todos los días
| mediante el scheduler de Laravel.
|
*/


/*
|--------------------------------------------------------------------------
| AUDITORÍA AUTOMÁTICA DEL SISTEMA
|--------------------------------------------------------------------------
|
| Verifica:
| - inventario
| - kardex
| - ventas
| - compras
| - inconsistencias de datos
|
*/

Schedule::command('sistema:check')->dailyAt('05:00');


/*
|--------------------------------------------------------------------------
| SUGERENCIAS AUTOMÁTICAS DE COMPRA
|--------------------------------------------------------------------------
|
| Detecta productos con stock bajo
| y genera sugerencias de reposición.
|
*/

Schedule::command('sistema:sugerir-compras')->dailyAt('05:00');
