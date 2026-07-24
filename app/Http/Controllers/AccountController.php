<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AccountController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $accounts = Auth::user()->accounts()->orderBy('name')->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'color' => 'nullable|string|max:7',
        ]);

        Auth::user()->accounts()->create($request->only(['name', 'type', 'balance', 'color']));

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function show(Account $account)
    {
        $this->authorize('view', $account);
        $transactions = $account->transactions()->latest()->paginate(10);
        return view('accounts.show', compact('account', 'transactions'));
    }

    public function edit(Account $account)
    {
        $this->authorize('update', $account);
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'color' => 'nullable|string|max:7',
        ]);

        $account->update($request->only(['name', 'type', 'balance', 'color']));

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Akun berhasil dihapus.');
    }
}
