<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        $labels = auth()->user()->labels()->withCount(['notes', 'reminders'])->get();
        return view('labels.index', compact('labels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $label = auth()->user()->labels()->create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Label created successfully',
                'label' => $label
            ]);
        }

        return redirect()->route('labels.index')->with('success', 'Label created successfully');
    }

    public function show(Label $label)
    {
        $this->authorize('view', $label);
        return response()->json($label);
    }

    public function update(Request $request, Label $label)
    {
        $this->authorize('update', $label);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $label->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Label updated successfully',
                'label' => $label
            ]);
        }

        return redirect()->route('labels.index')->with('success', 'Label updated successfully');
    }

    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);
        $label->delete();

        return redirect()->route('labels.index')->with('success', 'Label deleted successfully');
    }
}
