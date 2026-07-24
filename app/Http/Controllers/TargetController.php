<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $targets = Auth::user()->targets()->latest()->get();
        $accounts = Auth::user()->accounts;
        return view('targets.index', compact('targets', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
        ]);

        Auth::user()->targets()->create($request->only(['name', 'target_amount', 'current_amount', 'target_date']));

        return redirect()->route('targets.index')->with('success', 'Target berhasil dibuat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Target $target)
    {
        $this->authorizeOwner($target);

        if ($request->has('add_amount')) {
            $request->validate([
                'add_amount' => 'required|numeric|min:1',
            ]);

            $target->current_amount += $request->add_amount;
            $target->save();

            return redirect()->back()->with('success', 'Progress berhasil diperbarui.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'required|numeric|min:0',
            'target_date' => 'nullable|date',
        ]);

        $target->update($request->only(['name', 'target_amount', 'current_amount', 'target_date']));

        return redirect()->route('targets.index')->with('success', 'Target berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Target $target)
    {
        $this->authorizeOwner($target);
        $target->delete();

        return redirect()->route('targets.index')->with('success', 'Target berhasil dihapus.');
    }

    private function authorizeOwner(Target $target)
    {
        if ($target->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
