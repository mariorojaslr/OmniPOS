<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Clase RoomController
 * Gestiona el ABM de Habitaciones y Camas, asegurando aislamiento por empresa_id.
 */
class RoomController extends Controller
{
    /**
     * Listado general de habitaciones y camas.
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        // Cargar habitaciones de la empresa con sus camas asociadas
        $rooms = Room::where('empresa_id', $empresaId)
            ->with(['beds' => function ($query) {
                $query->orderBy('nombre', 'asc');
            }])
            ->orderBy('nombre', 'asc')
            ->get();

        return view('empresa.hospitalization.rooms', compact('rooms'));
    }

    /**
     * Registrar una nueva habitación.
     */
    public function storeRoom(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:comun,terapia_intensiva,terapia_intermedia,quirofano',
        ]);

        $empresaId = Auth::user()->empresa_id;

        Room::create([
            'empresa_id' => $empresaId,
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'activo' => true,
        ]);

        return redirect()->back()->with('success', 'Habitación creada con éxito.');
    }

    /**
     * Registrar una nueva cama dentro de una habitación.
     */
    public function storeBed(Request $request)
    {
        $request->validate([
            'habitacion_id' => 'required|exists:habitaciones,id',
            'nombre' => 'required|string|max:50',
        ]);

        $empresaId = Auth::user()->empresa_id;

        // Validar que la habitación pertenezca a la misma empresa
        $room = Room::where('id', $request->habitacion_id)
            ->where('empresa_id', $empresaId)
            ->firstOrFail();

        Bed::create([
            'empresa_id' => $empresaId,
            'habitacion_id' => $room->id,
            'nombre' => $request->nombre,
            'estado' => 'libre',
        ]);

        return redirect()->back()->with('success', 'Cama registrada con éxito.');
    }

    /**
     * Alternar el estado activo/inactivo de una habitación.
     */
    public function toggleRoomStatus(Room $room)
    {
        if ($room->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        $room->activo = !$room->activo;
        $room->save();

        return redirect()->back()->with('success', 'Estado de la habitación actualizado.');
    }

    /**
     * Alternar el estado de mantenimiento de una cama libre.
     */
    public function toggleBedMantenimiento(Bed $bed)
    {
        if ($bed->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        if ($bed->estado === 'ocupada') {
            return redirect()->back()->with('error', 'No se puede poner en mantenimiento una cama ocupada.');
        }

        $bed->estado = $bed->estado === 'mantenimiento' ? 'libre' : 'mantenimiento';
        $bed->save();

        return redirect()->back()->with('success', 'Estado de mantenimiento de la cama actualizado.');
    }

    /**
     * Eliminar una habitación y todas sus camas asociadas.
     */
    public function deleteRoom(Room $room)
    {
        if ($room->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        // Verificar si alguna cama de la habitación está ocupada
        $hasOcupied = Bed::where('habitacion_id', $room->id)
            ->where('estado', 'ocupada')
            ->exists();

        if ($hasOcupied) {
            return redirect()->back()->with('error', 'No se puede eliminar la habitación porque tiene camas ocupadas.');
        }

        $room->delete();

        return redirect()->back()->with('success', 'Habitación eliminada correctamente.');
    }

    /**
     * Eliminar una cama específica.
     */
    public function deleteBed(Bed $bed)
    {
        if ($bed->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Acción no autorizada.');
        }

        if ($bed->estado === 'ocupada') {
            return redirect()->back()->with('error', 'No se puede eliminar una cama ocupada.');
        }

        $bed->delete();

        return redirect()->back()->with('success', 'Cama eliminada correctamente.');
    }

    /**
     * Imprimir tarjeta QR de la cama.
     */
    public function printBedQr($id)
    {
        $empresa = Auth::user()->empresa;
        $bed = Bed::where('empresa_id', $empresa->id)->where('id', $id)->with('room')->firstOrFail();

        return view('empresa.hospitalization.qr_card', compact('bed', 'empresa'));
    }
}
