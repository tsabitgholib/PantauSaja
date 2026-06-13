<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Auth::user()->subscriptions()->orderBy('billing_date')->get();
        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'billing_date' => 'required|integer|min:1|max:31',
        ]);

        Auth::user()->subscriptions()->create($request->all());

        return redirect()->route('subscriptions.index')->with('success', 'Langganan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $this->authorizeOwner($subscription);

        if ($request->has('toggle_status')) {
            $subscription->status = $subscription->status === 'active' ? 'inactive' : 'active';
            $subscription->save();
            return redirect()->back()->with('success', 'Status berhasil diubah.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'billing_date' => 'required|integer|min:1|max:31',
        ]);

        $subscription->update($request->all());

        return redirect()->route('subscriptions.index')->with('success', 'Langganan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $this->authorizeOwner($subscription);
        $subscription->delete();

        return redirect()->route('subscriptions.index')->with('success', 'Langganan berhasil dihapus.');
    }

    private function authorizeOwner(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
