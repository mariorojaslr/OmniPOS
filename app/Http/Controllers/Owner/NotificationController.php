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
 
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'nullable|exists:empresas,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'message' => 'required|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:10240', // Max 10MB
        ]);

        $mediaUrl = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('notifications', 'public');
            $mediaUrl = asset('storage/' . $path);
            $mime = $request->file('media')->getMimeType();
            $mediaType = str_contains($mime, 'video') ? 'video' : 'image';
        }

        OwnerNotification::create([
            'empresa_id' => $request->empresa_id, // NULL = Global
            'title' => $request->title,
            'type' => $request->type,
            'message' => $request->message,
            'channel' => 'dashboard',
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
        ]);

        if ($request->empresa_id && $request->type === 'vencimiento') {
            $empresa = Empresa::find($request->empresa_id);
            $empresa->update(['ultima_notificacion_vencimiento' => now()]);
        }

        return redirect()->back()->with('success', 'Comunicación enviada correctamente.');
    }
}
