<?php

namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use App\Models\SystemUpdate;
use Illuminate\Http\Request;
 
class SystemUpdateController extends Controller
{
    public function index()
    {
        $updates = SystemUpdate::orderByDesc('publish_date')->paginate(15);
        return view('owner.updates.index', compact('updates'));
    }
 
    public function create()
    {
        return view('owner.updates.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publish_date' => 'required|date',
            'type' => 'required|in:nuevo,mejora,arreglo,tarea',
        ]);
 
        SystemUpdate::create($request->all());
 
        return redirect()->route('owner.updates.index')->with('success', 'Novedad publicada correctamente.');
    }
 
    public function edit(SystemUpdate $update)
    {
        return view('owner.updates.edit', compact('update'));
    }
 
    public function update(Request $request, SystemUpdate $update)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publish_date' => 'required|date',
            'type' => 'required|in:nuevo,mejora,arreglo,tarea',
        ]);
 
        $update->update($request->all());
 
        return redirect()->route('owner.updates.index')->with('success', 'Novedad actualizada.');
    }
 
    public function destroy(SystemUpdate $update)
    {
        $update->delete();
        return redirect()->route('owner.updates.index')->with('success', 'Novedad eliminada.');
    }
}
