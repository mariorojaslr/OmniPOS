<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaConfig extends Model
{
    protected $table = 'empresa_config';

    protected $fillable = [
        'empresa_id',
        'logo',
        'color_primary',
        'color_secondary',
        'theme',
        'dias_nuevo',
        'mod_orden_pedido',
        'mod_orden_pedido_extra',
        'mod_turnos',
        'mod_unidades_medida',
        'mod_internaciones',
        'mod_campos_dinamicos',
        'mod_afip',
        'mod_pagos',
        'mod_backups',
        'mod_ventas',
        'mod_tesoreria',
        'mod_logistica',
        'mod_compras',
        'mod_afiliados',
        'mod_hce',
        'tipo_clinica',
        'mod_odontograma',
        'terminologia_espacio',
    ];

    protected $casts = [
        'mod_orden_pedido' => 'boolean',
        'mod_orden_pedido_extra' => 'boolean',
        'mod_turnos' => 'boolean',
        'mod_unidades_medida' => 'boolean',
        'mod_internaciones' => 'boolean',
        'mod_campos_dinamicos' => 'boolean',
        'mod_afip' => 'boolean',
        'mod_pagos' => 'boolean',
        'mod_backups' => 'boolean',
        'mod_ventas' => 'boolean',
        'mod_tesoreria' => 'boolean',
        'mod_logistica' => 'boolean',
        'mod_compras' => 'boolean',
        'mod_afiliados' => 'boolean',
        'mod_hce' => 'boolean',
        'mod_odontograma' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | HELPERS DE ESPECIALIDAD CLÍNICA
    |--------------------------------------------------------------------------
    */
    public function isOdontologica(): bool
    {
        return $this->tipo_clinica === 'clinica_odontologica' || (bool) $this->mod_odontograma;
    }

    public function hasOdontograma(): bool
    {
        return (bool) $this->mod_odontograma || $this->tipo_clinica === 'clinica_odontologica';
    }

    public function getTipoClinicaNombre(): string
    {
        return match ($this->tipo_clinica) {
            'clinica_odontologica' => 'Clínica Odontológica',
            'unidad_coronaria'     => 'Unidad Coronaria / Cardiología',
            'centro_rehabilitacion'=> 'Centro de Rehabilitación / Kinesiología',
            default                => 'Clínica Médica General',
        };
    }

    public function getEspacioLabel(bool $plural = false): string
    {
        if ($this->isOdontologica()) {
            return $plural ? 'Consultorios Dentales' : 'Consultorio Dental';
        }

        return match ($this->terminologia_espacio) {
            'sillon' => $plural ? 'Sillones' : 'Sillón',
            'box'    => $plural ? 'Boxes' : 'Box',
            'sala'   => $plural ? 'Salas' : 'Sala',
            default  => $plural ? 'Consultorios' : 'Consultorio',
        };
    }

    public function getProfesionalLabel(bool $plural = false): string
    {
        if ($this->isOdontologica()) {
            return $plural ? 'Odontólogos' : 'Odontólogo/a';
        }

        return match ($this->tipo_clinica) {
            'centro_rehabilitacion' => $plural ? 'Kinesiólogos / Terapeutas' : 'Kinesiólogo/a',
            default                 => $plural ? 'Médicos Profesionales' : 'Médico/a',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON EMPRESA
    |--------------------------------------------------------------------------
    */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('images/logo_premium.png');
        }

        // Si ya es una URL absoluta, la devolvemos
        if (\Illuminate\Support\Str::startsWith($this->logo, ['http://', 'https://'])) {
            return $this->logo;
        }

        // El logo siempre se guarda localmente en el disco public de Hostinger a través de ConfiguracionEmpresaController.
        // Por ende, para máxima velocidad y evitar problemas con CDN, lo servimos localmente.
        return '/local-media/' . ltrim($this->logo, '/');
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }
}
