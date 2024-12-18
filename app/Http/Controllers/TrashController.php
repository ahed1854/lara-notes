<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashController extends Controller
{
    public function index()
    {
        $notes = Note::onlyTrashed()
            ->with('labels')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $reminders = Reminder::onlyTrashed()
            ->with('labels')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('trash.index', compact('notes', 'reminders'));
    }

    public function restoreNote($id)
    {
        $note = Note::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $note);
        $note->restore();
        return back()->with('success', 'Note restored successfully.');
    }

    public function restoreReminder($id)
    {
        $reminder = Reminder::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $reminder);
        $reminder->restore();
        return back()->with('success', 'Reminder restored successfully.');
    }

    public function destroyNote($id)
    {
        $note = Note::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $note);
        $note->forceDelete();
        return back()->with('success', 'Note permanently deleted.');
    }

    public function destroyReminder($id)
    {
        $reminder = Reminder::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $reminder);
        $reminder->forceDelete();
        return back()->with('success', 'Reminder permanently deleted.');
    }

    public function destroyAllNotes()
    {
        $notes = Note::onlyTrashed()
            ->where('user_id', auth()->id())
            ->get();

        if ($notes->isEmpty()) {
            return back()->with('info', 'No notes to delete.');
        }

        DB::beginTransaction();
        try {
            foreach ($notes as $note) {
                $this->authorize('forceDelete', $note);
            }

            Note::onlyTrashed()
                ->where('user_id', auth()->id())
                ->forceDelete();

            DB::commit();
            return back()->with('success', 'All notes have been permanently deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete notes. Please try again.');
        }
    }

    public function destroyAllReminders()
    {
        $reminders = Reminder::onlyTrashed()
            ->where('user_id', auth()->id())
            ->get();

        if ($reminders->isEmpty()) {
            return back()->with('info', 'No reminders to delete.');
        }

        DB::beginTransaction();
        try {
            foreach ($reminders as $reminder) {
                $this->authorize('forceDelete', $reminder);
            }

            Reminder::onlyTrashed()
                ->where('user_id', auth()->id())
                ->forceDelete();

            DB::commit();
            return back()->with('success', 'All reminders have been permanently deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete reminders. Please try again.');
        }
    }
} 