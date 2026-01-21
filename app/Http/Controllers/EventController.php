<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Tampilkan semua event
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'Event berhasil ditampilkan',
            'data' => EventResource::collection($events),
            'meta' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    // Buat event baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date'  => 'required|date',
        ]);

        $event = Event::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dibuat',
            'data' => new EventResource($event)
        ], 201);
    }

    // Tampilkan event berdasarkan ID
    public function show(Event $event)
    {
        return response()->json([
            'success' => true,
            'message' => 'Event berhasil ditampilkan',
            'data' => new EventResource($event->load('users'))
        ]);
    }

    // Update event
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'event_date'  => 'sometimes|required|date',
        ]);

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil diupdate',
            'data' => new EventResource($event)
        ]);
    }

    // Hapus event
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dihapus'
        ]);
    }

    // User bergabung dengan event
    public function join(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = $request->user();

        // Validasi apakah user sudah bergabung
        if ($event->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah bergabung dengan event ini'
            ], 400);
        }

        $event->users()->attach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung dengan event',
            'data' => new EventResource($event->load('users'))
        ]);
    }
}
