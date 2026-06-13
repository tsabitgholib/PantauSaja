<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $debts = Auth::user()->debts()->where('type', 'debt')->orderBy('status')->latest()->get();
        $receivables = Auth::user()->debts()->where('type', 'receivable')->orderBy('status')->latest()->get();

        return view('debts.index', compact('debts', 'receivables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'person_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:debt,receivable',
            'due_date' => 'nullable|date',
        ]);

        Auth::user()->debts()->create($request->all());

        return redirect()->route('debts.index')->with('success', 'Catatan berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Debt $debt)
    {
        $this->authorizeOwner($debt);

        if ($request->has('toggle_status')) {
            $debt->status = $debt->status === 'paid' ? 'pending' : 'paid';
            $debt->save();
            return redirect()->back()->with('success', 'Status berhasil diubah.');
        }

        $request->validate([
            'person_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $debt->update($request->all());

        return redirect()->route('debts.index')->with('success', 'Catatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Debt $debt)
    {
        $this->authorizeOwner($debt);
        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'Catatan berhasil dihapus.');
    }

    private function authorizeOwner(Debt $debt)
    {
        if ($debt->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
