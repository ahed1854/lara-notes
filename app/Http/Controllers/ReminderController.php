<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = auth()->user()->reminders()
            ->with('labels')
            ->orderBy('reminder_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $labels = auth()->user()->labels()->get();

        return view('reminders.index', compact('reminders', 'labels'));
    }

    public function store(Request $request)
    {
        $title = trim($request->input('title', ''));
        
        if (empty($title)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Reminder title cannot be empty.'], 422);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Reminder title cannot be empty.');
        }

        if (empty($request->input('reminder_at'))) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Reminder date is required.'], 422);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Reminder date is required.');
        }

        $reminder = auth()->user()->reminders()->create([
            'title' => $title,
            'description' => $request->input('description'),
            'reminder_at' => $request->input('reminder_at'),
            'note_id' => $request->input('note_id'),
        ]);

        if ($request->has('labels')) {
            $reminder->labels()->attach($request->input('labels'));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reminder created successfully',
                'reminder' => $reminder
            ]);
        }

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
    }

    public function update(Request $request, Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $data = $request->json()->all();
        if (!$data) {
            $data = $request->all();
        }

        $validated = $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_at' => 'required|date',
            'is_completed' => 'boolean',
            'note_id' => 'nullable|exists:notes,id',
        ]);

        if (!isset($validated['is_completed'])) {
            $validated['is_completed'] = false;
        }

        $reminder->update($validated);

        $labels = $data['labels'] ?? [];
        $reminder->labels()->sync($labels);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reminder updated successfully',
                'reminder' => $reminder->load('labels')
            ]);
        }

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully');
    }

    public function destroy(Reminder $reminder)
    {
        $this->authorize('delete', $reminder);
        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Reminder moved to trash.');
    }

    public function restore($id)
    {
        $reminder = Reminder::withTrashed()->findOrFail($id);
        $this->authorize('restore', $reminder);
        $reminder->restore();

        return redirect()->route('reminders.index')->with('success', 'Reminder restored successfully.');
    }

    public function forceDelete($id)
    {
        $reminder = Reminder::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $reminder);
        $reminder->forceDelete();

        return redirect()->route('reminders.trash')->with('success', 'Reminder permanently deleted.');
    }

    public function trash()
    {
        $trashedReminders = auth()->user()->reminders()->onlyTrashed()->get();
        return view('reminders.trash', compact('trashedReminders'));
    }

    public function forceDeleteAll()
    {
        $reminders = auth()->user()->reminders()->onlyTrashed()->get();
        foreach ($reminders as $reminder) {
            $this->authorize('forceDelete', $reminder);
            $reminder->forceDelete();
        }

        return redirect()->route('reminders.trash')->with('success', 'All reminders have been permanently deleted.');
    }

    public function toggleComplete(Reminder $reminder)
    {
        $this->authorize('update', $reminder);
        $reminder->update(['is_completed' => !$reminder->is_completed]);

        return redirect()->back()->with('success', 'Reminder status updated.');
    }

    public function show(Reminder $reminder)
    {
        $this->authorize('view', $reminder);
        $reminder->load('labels');
        return response()->json($reminder);
    }
} 