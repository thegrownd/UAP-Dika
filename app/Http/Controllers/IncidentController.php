<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'admin') {
            $incidents = Incident::latest()->paginate(10);
        } else {
            $incidents = Incident::where('user_id', $user->id)->latest()->paginate(10);
        }
        return response()->json($incidents);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'occurred_at' => ['nullable', 'date'],
        ]);

        $incident = new Incident($data);
        $incident->user_id = $request->user()->id;

        $this->authorize('create', $incident);

        $incident->save();

        return response()->json($incident, 201);
    }

    public function show(Incident $incident)
    {
        $this->authorize('view', $incident);
        return response()->json($incident);
    }

    public function update(Request $request, Incident $incident)
    {
        $this->authorize('update', $incident);

        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'severity' => ['sometimes', 'required', 'in:low,medium,high,critical'],
            'status' => ['sometimes', 'required', 'in:open,in_progress,resolved,closed'],
            'occurred_at' => ['nullable', 'date'],
        ]);

        $incident->fill($data)->save();
        return response()->json($incident);
    }

    public function destroy(Incident $incident)
    {
        $this->authorize('delete', $incident);
        $incident->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

