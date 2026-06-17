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
        $accounts = Auth::user()->accounts;

        return view('debts.index', compact('debts', 'receivables', 'accounts'));
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
            'account_id' => 'required|exists:accounts,id',
            'due_date' => 'nullable|date',
        ]);

        $debt = Auth::user()->debts()->create($request->all());

        // Create transaction to affect balance
        $transactionType = ($request->type === 'debt') ? 'income' : 'expense';
        $note = ($request->type === 'debt') ? "Pinjam dari " : "Memberi pinjaman ke ";
        $note .= $request->person_name;

        Auth::user()->transactions()->create([
            'account_id' => $request->account_id,
            'type' => $transactionType,
            'amount' => $request->amount,
            'date' => now(),
            'note' => $note,
        ]);

        return redirect()->route('debts.index')->with('success', 'Catatan berhasil disimpan dan saldo akun telah diperbarui.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Debt $debt)
    {
        $this->authorizeOwner($debt);

        if ($request->has('toggle_status')) {
            $newStatus = $debt->status === 'paid' ? 'pending' : 'paid';
            
            if ($newStatus === 'paid') {
                $request->validate([
                    'account_id' => 'required|exists:accounts,id',
                ]);

                // Create transaction for repayment
                $transactionType = ($debt->type === 'debt') ? 'expense' : 'income';
                $note = ($debt->type === 'debt') ? "Bayar utang ke " : "Terima pelunasan piutang dari ";
                $note .= $debt->person_name;

                Auth::user()->transactions()->create([
                    'account_id' => $request->account_id,
                    'type' => $transactionType,
                    'amount' => $debt->amount,
                    'date' => now(),
                    'note' => $note,
                ]);
            }

            $debt->status = $newStatus;
            $debt->save();
            
            return redirect()->back()->with('success', 'Status berhasil diubah dan saldo akun telah diperbarui.');
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
