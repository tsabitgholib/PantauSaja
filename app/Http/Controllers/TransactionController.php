<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->with(['account', 'category', 'destinationAccount']);

        // Filtering
        if ($request->has('account_id') && $request->account_id != '') {
            $query->where('account_id', $request->account_id)
                  ->orWhere('to_account_id', $request->account_id);
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->latest('date')->latest('id')->paginate(15);
        
        $accounts = Auth::user()->accounts;
        $categories = Category::where(function($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.index', compact('transactions', 'accounts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Auth::user()->accounts;
        $categories = Category::where(function($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.create', compact('accounts', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required_if:type,income,expense|nullable|exists:categories,id',
            'to_account_id' => 'required_if:type,transfer|nullable|exists:accounts,id|different:account_id',
            'note' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        Transaction::create($data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $this->authorizeOwner($transaction);
        $accounts = Auth::user()->accounts;
        $categories = Category::where(function($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeOwner($transaction);

        $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required_if:type,income,expense|nullable|exists:categories,id',
            'to_account_id' => 'required_if:type,transfer|nullable|exists:accounts,id|different:account_id',
            'note' => 'nullable|string',
        ]);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorizeOwner($transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function authorizeOwner(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
