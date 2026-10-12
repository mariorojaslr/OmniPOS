<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToEmpresa;

class Turno extends Model
{
    use HasFactory, BelongsToEmpresa;

    protected $fillable = [
        'empresa_id',
        'user_id',
        'client_id',
        'servicio_id',
        'cliente_nombre_manual',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'monto',
        'comision_monto',
        'liquidacion_id',
        'notas',
        'obra_social_id',
        'nro_afiliado_os',
        'venta_id',
        'hora_arribo',
        'estado_cola'
    ];

    public function studies()
    {
        return $this->hasMany(ClientStudy::class, 'turno_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class);
    }

    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class, 'obra_social_id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    /**
     * Generador de Enlace Directo de WhatsApp con Recordatorio Contextual
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        $phone = $this->cliente?->phone;
        if (!$phone) return null;

        // Limpiar teléfono
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '549' . $cleanPhone;
        }

        $empresaNombre = $this->empresa?->nombre_comercial ?? 'el centro de salud';
        $pacienteNombre = $this->cliente?->name ?? $this->cliente_nombre_manual ?? 'Estimado/a';
        $doctorNombre = $this->user?->name ?? 'Profesional';
        $consultorio = $this->user?->consultorio ?? 'Consultorio Asignado';
        $fecha = \Carbon\Carbon::parse($this->fecha)->format('d/m/Y');
        $hora = \Carbon\Carbon::parse($this->hora_inicio)->format('H:i');
        $linkSeguimiento = url('/turno/' . $this->id . '/seguimiento');

        $esOdonto = $this->empresa?->isOdontologica() ?? false;
        $tipoAtencion = $esOdonto ? 'tu consulta odontológica' : 'tu consulta médica';
        $espacio = $esOdonto ? 'Consultorio Dental' : 'Consultorio';

        $mensaje = "👋 ¡Hola {$pacienteNombre}! Te saludamos desde *{$empresaNombre}*.\n\n"
                 . "📅 Te recordamos {$tipoAtencion} con *Dr/a. {$doctorNombre}*:\n"
                 . "▫️ *Fecha:* {$fecha}\n"
                 . "▫️ *Horario:* {$hora} hs\n"
                 . "▫️ *Ubicación:* {$espacio} ({$consultorio})\n\n"
                 . "📲 Seguimiento de tu turno en tiempo real:\n{$linkSeguimiento}\n\n"
                 . "Por favor respondé este mensaje para confirmar tu turno.";

        return 'https://api.whatsapp.com/send?phone=' . $cleanPhone . '&text=' . urlencode($mensaje);
    }
}
