<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Stats
        $totalBalance = $user->accounts()->sum('balance');
        
        $incomeThisMonth = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $expenseThisMonth = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $savingRate = ($incomeThisMonth > 0) ? (($incomeThisMonth - $expenseThisMonth) / $incomeThisMonth) * 100 : 0;
        
        $totalDebt = $user->debts()->where('type', 'debt')->where('status', 'pending')->sum('amount');
        $totalReceivable = $user->debts()->where('type', 'receivable')->where('status', 'pending')->sum('amount');

        // Chart: Cash Flow (Last 6 Months)
        $cashFlowData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthIncome = $user->transactions()
                ->where('type', 'income')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');
            $monthExpense = $user->transactions()
                ->where('type', 'expense')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');
            
            $cashFlowData['labels'][] = $month->format('M Y');
            $cashFlowData['income'][] = $monthIncome;
            $cashFlowData['expense'][] = $monthExpense;
        }

        // Chart: Expense by Category
        $categoriesData = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $categoryChart = [
            'labels' => $categoriesData->map(fn($item) => $item->category->name ?? 'Lainnya'),
            'data' => $categoriesData->map(fn($item) => $item->total),
        ];

        // Recent Transactions
        $recentTransactions = $user->transactions()
            ->with(['account', 'category'])
            ->latest('date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBalance', 
            'incomeThisMonth', 
            'expenseThisMonth', 
            'savingRate',
            'totalDebt',
            'totalReceivable',
            'cashFlowData',
            'categoryChart',
            'recentTransactions'
        ));
    }
}
