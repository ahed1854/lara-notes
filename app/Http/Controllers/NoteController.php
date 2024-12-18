<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->notes()
            ->with('labels')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $labels = auth()->user()->labels()->get();

        return view('notes.index', compact('notes', 'labels'));
    }

    public function show(Note $note)
    {
        $this->authorize('view', $note);
        $note->load('labels');
        return response()->json($note);
    }

    public function store(Request $request)
    {
        $content = trim($request->input('content', ''));
        
        if (empty($content)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Note content cannot be empty.'], 422);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Note content cannot be empty.');
        }

        $note = auth()->user()->notes()->create([
            'title' => $request->input('title'),
            'content' => $content,
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        if ($request->has('labels')) {
            $note->labels()->attach($request->input('labels'));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Note created successfully',
                'note' => $note
            ]);
        }

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $data = $request->json()->all();
        if (!$data) {
            $data = $request->all();
        }

        $validated = $this->validate($request, [
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean',
        ]);

        $note->update($validated);

        // Handle labels
        $labels = $data['labels'] ?? [];
        $note->labels()->sync($labels);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Note updated successfully',
                'note' => $note->load('labels')
            ]);
        }

        return redirect()->route('notes.index')->with('success', 'Note updated successfully');
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note moved to trash.');
    }

    public function restore($id)
    {
        $note = Note::withTrashed()->findOrFail($id);
        $this->authorize('restore', $note);
        $note->restore();

        return redirect()->route('notes.index')->with('success', 'Note restored successfully.');
    }

    public function forceDelete($id)
    {
        $note = Note::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $note);
        $note->forceDelete();

        return redirect()->route('notes.trash')->with('success', 'Note permanently deleted.');
    }

    public function trash()
    {
        $trashedNotes = auth()->user()->notes()->onlyTrashed()->get();
        return view('notes.trash', compact('trashedNotes'));
    }

    public function forceDeleteAll()
    {
        $notes = auth()->user()->notes()->onlyTrashed()->get();
        foreach ($notes as $note) {
            $this->authorize('forceDelete', $note);
            $note->forceDelete();
        }

        return redirect()->route('notes.trash')->with('success', 'All notes have been permanently deleted.');
    }
} 