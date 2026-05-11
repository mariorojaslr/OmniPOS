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
            'expires_at' => 'nullable|date',
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
            'empresa_id' => $request->empresa_id,
            'title' => $request->title,
            'type' => $request->type,
            'message' => $request->message,
            'expires_at' => $request->expires_at,
            'channel' => 'dashboard',
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
        ]);

        return redirect()->back()->with('success', 'Comunicación enviada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $notif = OwnerNotification::findOrFail($id);
        $notif->update($request->only(['title', 'message', 'type', 'expires_at', 'active']));
        
        return redirect()->back()->with('success', 'Comunicación actualizada.');
    }

    public function destroy($id)
    {
        $notif = OwnerNotification::findOrFail($id);
        $notif->delete();
        
        return redirect()->back()->with('success', 'Comunicación eliminada.');
    }

    public function toggleActive($id)
    {
        $notif = OwnerNotification::findOrFail($id);
        $notif->active = !$notif->active;
        $notif->save();
        
        return redirect()->back()->with('success', 'Estado de comunicación actualizado.');
    }
}
