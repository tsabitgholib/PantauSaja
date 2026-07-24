<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->with(['account', 'category', 'destinationAccount']);

        if ($request->filled('account_id')) {
            $query->where(function($q) use ($request) {
                $q->where('account_id', $request->account_id)
                  ->orWhere('to_account_id', $request->account_id);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->latest('date')->latest('id')->paginate(15);

        $accounts = Auth::user()->accounts;
        $categories = Category::where(function($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.index', compact('transactions', 'accounts', 'categories'));
    }

    public function create()
    {
        $accounts = Auth::user()->accounts;
        $categories = Category::where(function($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.create', compact('accounts', 'categories'));
    }

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

        $data = $request->only(['type', 'account_id', 'category_id', 'to_account_id', 'amount', 'date', 'note']);
        $data['user_id'] = Auth::id();

        Transaction::create($data);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $accounts = Auth::user()->accounts;
        $categories = Category::where(function($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required_if:type,income,expense|nullable|exists:categories,id',
            'to_account_id' => 'required_if:type,transfer|nullable|exists:accounts,id|different:account_id',
            'note' => 'nullable|string',
        ]);

        $transaction->update($request->only(['type', 'account_id', 'category_id', 'to_account_id', 'amount', 'date', 'note']));

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
