<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OwnerNotification;

class NotificationReadController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        $notification = OwnerNotification::findOrFail($id);

        // Si es individual, marcar read_at global (si queremos) o solo para este usuario
        // Para simplificar y que funcione para todos, usamos la tabla pivot siempre
        $user->readOwnerNotifications()->syncWithoutDetaching([$id]);

        return response()->json(['success' => true]);
    }
}
