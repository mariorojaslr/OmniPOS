<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\OwnerNotification;
 
class NotificationController extends Controller
{
    public function index()
    {
        $notifications = OwnerNotification::with('empresa')->latest()->paginate(20);
        return view('owner.notifications.index', compact('notifications'));
    }
 
    public function send(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'type' => 'required|string',
            'message' => 'required|string',
        ]);
 
        OwnerNotification::create([
            'empresa_id' => $request->empresa_id,
            'type' => $request->type,
            'message' => $request->message,
            'channel' => 'dashboard',
        ]);
 
        if ($request->type === 'vencimiento') {
            $empresa = Empresa::find($request->empresa_id);
            $empresa->update(['ultima_notificacion_vencimiento' => now()]);
        }
 
        return redirect()->back()->with('success', 'Notificación enviada correctamente.');
    }
}
