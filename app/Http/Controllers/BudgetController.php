<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $budgets = Auth::user()->budgets()
            ->where('period', $startOfMonth->format('Y-m-d'))
            ->with('category')
            ->get()
            ->map(function ($budget) use ($startOfMonth, $endOfMonth) {
                $used = Transaction::where('user_id', Auth::id())
                    ->where('category_id', $budget->category_id)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->where('type', 'expense')
                    ->sum('amount');
                
                $budget->used = $used;
                $budget->percentage = ($budget->amount > 0) ? ($used / $budget->amount) * 100 : 0;
                return $budget;
            });

        $categories = Category::where('type', 'expense')
            ->where(function($q) {
                $q->whereNull('user_id')->orWhere('user_id', Auth::id());
            })->get();

        return view('budgets.index', compact('budgets', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $period = Carbon::now()->startOfMonth()->format('Y-m-d');

        Auth::user()->budgets()->updateOrCreate(
            ['category_id' => $request->category_id, 'period' => $period],
            ['amount' => $request->amount]
        );

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }
        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil dihapus.');
    }
}
