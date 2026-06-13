@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <button class="back-btn" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
    </button>
    <div class="flex-grow-1">
        <h2 class="fw-bold mb-0">Laporan Keuangan</h2>
    </div>
    <form action="{{ route('reports.export-csv') }}" method="GET">
        <input type="hidden" name="start_date" value="{{ $start_date }}">
        <input type="hidden" name="end_date" value="{{ $end_date }}">
        <input type="hidden" name="account_id" value="{{ request('account_id') }}">
        <input type="hidden" name="type" value="{{ request('type') }}">
        <button type="submit" class="neo-btn neo-btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-file-csv"></i> Export
        </button>
    </form>
</div>

<!-- Filter Card -->
<div class="neo-card mb-4">
    <div class="p-3 p-md-4">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="neo-input" value="{{ $start_date }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="neo-input" value="{{ $end_date }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Jenis</label>
                <select name="type" class="neo-select">
                    <option value="">Semua</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Akun</label>
                <select name="account_id" class="neo-select">
                    <option value="">Semua</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="neo-btn flex-grow-1">Filter</button>
                <a href="{{ route('reports.index') }}" class="neo-btn neo-btn-primary flex-grow-1 text-decoration-none">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Row -->
<div class="row mb-4 g-3">
    <div class="col-md-6">
        <div class="neo-card stats-card-green h-100 p-3 p-md-4">
            <p class="small fw-black mb-1 opacity-75">Total Pemasukan</p>
            <h4 class="fw-black mb-0">Rp {{ number_format($total_income, 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="neo-card stats-card-red h-100 p-3 p-md-4">
            <p class="small fw-black mb-1 opacity-75">Total Pengeluaran</p>
            <h4 class="fw-black mb-0">Rp {{ number_format($total_expense, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>

<div class="neo-card">
    <div class="p-3 p-md-4">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th>Akun</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->date->format('d M Y') }}</td>
                            <td>{{ $transaction->note ?? '-' }}</td>
                            <td>{{ $transaction->category->name ?? '-' }}</td>
                            <td>{{ $transaction->account->name }}</td>
                            <td class="text-end fw-black {{ $transaction->type === 'income' ? 'text-success' : ($transaction->type === 'expense' ? 'text-danger' : 'text-primary') }}">
                                {{ $transaction->type === 'expense' ? '-' : ($transaction->type === 'income' ? '+' : '') }}
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Data tidak tersedia untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
