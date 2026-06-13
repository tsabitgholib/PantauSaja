<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $query = Auth::user()->transactions()->with(['account', 'category'])
            ->whereBetween('date', [$start_date, $end_date]);

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('account_id') && $request->account_id != '') {
            $query->where('account_id', $request->account_id);
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $total_income = $transactions->where('type', 'income')->sum('amount');
        $total_expense = $transactions->where('type', 'expense')->sum('amount');

        $accounts = Auth::user()->accounts;
        $categories = Category::where(function($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('reports.index', compact(
            'transactions', 'total_income', 'total_expense', 
            'accounts', 'categories', 'start_date', 'end_date'
        ));
    }

    public function exportCsv(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        $query = Auth::user()->transactions()->with(['account', 'category'])
            ->whereBetween('date', [$start_date, $end_date]);

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('account_id') && $request->account_id != '') {
            $query->where('account_id', $request->account_id);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $filename = "laporan_keuangan_" . $start_date . "_to_" . $end_date . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Keterangan', 'Jenis', 'Kategori', 'Akun', 'Nominal'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $t) {
                $row['Tanggal']    = $t->date->format('Y-m-d');
                $row['Keterangan'] = $t->note;
                $row['Jenis']      = ucfirst($t->type);
                $row['Kategori']   = $t->category->name ?? '-';
                $row['Akun']       = $t->account->name;
                $row['Nominal']    = $t->amount;

                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
