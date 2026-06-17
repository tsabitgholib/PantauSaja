@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex align-items-center gap-3 mb-4">
    <button class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </button>
    <div class="flex-grow-1">
        <h2 class="fw-black mb-0" style="font-size: clamp(20px, 4vw, 28px);">Transaksi</h2>
    </div>
    <a href="{{ route('accounts.index') }}" class="neo-btn neo-btn-primary d-flex align-items-center gap-1 py-2 px-3">
        <i class="fas fa-credit-card"></i>
        <span class="d-none d-md-inline">List Akun</span>
    </a>
    <a href="{{ route('transactions.create') }}" class="neo-btn neo-btn-success d-flex align-items-center gap-1 py-2 px-3">
        <i class="fas fa-plus"></i>
        <span class="d-none d-md-inline">Tambah</span>
    </a>
</div>

<!-- Filter Card -->
<div class="neo-card mb-4">
    <div class="p-3 p-md-4">
        <form action="{{ route('transactions.index') }}" method="GET" class="row g-3">
            <div class="col-md-3 col-6">
                <label class="form-label small fw-black mb-1" style="font-size: 12px;">Akun</label>
                <select name="account_id" class="neo-select form-select">
                    <option value="">Semua Akun</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label small fw-black mb-1" style="font-size: 12px;">Jenis</label>
                <select name="type" class="neo-select form-select">
                    <option value="">Semua Jenis</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label small fw-black mb-1" style="font-size: 12px;">Kategori</label>
                <select name="category_id" class="neo-select form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ ucfirst($category->type) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-6 d-flex align-items-end gap-2">
                <button type="submit" class="neo-btn neo-btn-primary flex-grow-1 py-2 px-3">Filter</button>
                <a href="{{ route('transactions.index') }}" class="neo-btn flex-grow-1 py-2 px-3">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Transactions List - Mobile Cards -->
<div class="d-md-none mb-4">
    @forelse($transactions as $transaction)
        <div class="neo-card mb-3">
            <div class="p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="border-3 border-dark rounded-2 d-flex align-items-center justify-content-center {{ $transaction->type === 'income' ? 'bg-green-100' : ($transaction->type === 'expense' ? 'bg-red-100' : 'bg-blue-100') }}" style="width: 42px; height: 42px; border-radius: 10px;">
                            <i class="fas fa-{{ $transaction->type === 'income' ? 'arrow-down' : ($transaction->type === 'expense' ? 'arrow-up' : 'exchange-alt') }} {{ $transaction->type === 'income' ? 'text-success' : ($transaction->type === 'expense' ? 'text-danger' : 'text-primary') }}"></i>
                        </div>
                        <div>
                            <div class="fw-black" style="font-size: 13px;">{{ $transaction->updated_at->format('d M Y, H:i') }}</div>
                            <span class="neo-badge {{ $transaction->type === 'income' ? 'neo-badge-success' : ($transaction->type === 'expense' ? 'neo-badge-danger' : 'neo-badge-primary') }}" style="font-size: 10px; padding: 2px 8px;">
                                <i class="fas fa-{{ $transaction->type === 'income' ? 'arrow-down' : ($transaction->type === 'expense' ? 'arrow-up' : 'exchange-alt') }}"></i> {{ $transaction->type === 'income' ? 'Pemasukan' : ($transaction->type === 'expense' ? 'Pengeluaran' : 'Transfer') }}
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <p class="fw-black mb-0 {{ $transaction->type === 'income' ? 'text-success' : ($transaction->type === 'expense' ? 'text-danger' : 'text-primary') }}" style="font-size: 14px;">
                            {{ $transaction->type === 'income' ? '+' : ($transaction->type === 'expense' ? '-' : '') }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                
                <p class="fw-bold mb-2" style="font-size: 13px;">{{ $transaction->note ?? ($transaction->category->name ?? '-') }}</p>
                
                <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                    @if($transaction->category)
                        <span class="neo-badge" style="font-size: 10px; padding: 2px 8px;">
                            <i class="{{ $transaction->category->icon }}"></i> {{ $transaction->category->name }}
                        </span>
                    @endif
                    <span class="neo-badge-primary neo-badge" style="font-size: 10px; padding: 2px 8px;">{{ $transaction->account->name }}</span>
                </div>
                
                @if($transaction->type === 'transfer')
                    <p class="text-muted mb-0" style="font-size: 11px;">
                        Transfer: {{ $transaction->account->name }} <i class="fas fa-long-arrow-alt-right mx-1"></i> {{ $transaction->destinationAccount->name }}
                    </p>
                @endif
                
                <div class="d-flex gap-2 mt-3 pt-2 border-top border-2 border-dark">
                    <a href="{{ route('transactions.edit', $transaction) }}" class="neo-btn flex-grow-1 py-1 px-3" style="font-size: 12px;">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')" class="flex-grow-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="neo-btn neo-btn-danger w-100 py-1 px-3" style="font-size: 12px;">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="neo-card">
            <div class="p-4 text-center">
                <i class="fas fa-inbox fa-3x text-muted opacity-50 mb-3 d-block"></i>
                <h5 class="fw-bold mb-1" style="font-size: 16px;">Belum ada transaksi</h5>
                <p class="mb-3" style="font-size: 13px;">Mulai catat transaksi keuanganmu!</p>
                <a href="{{ route('transactions.create') }}" class="neo-btn neo-btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Transaksi Pertama
                </a>
            </div>
        </div>
    @endforelse
    
    @if($transactions->hasPages())
        <div class="neo-card mt-3">
            <div class="p-3 text-center">
                {{ $transactions->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Transactions List - Desktop Table -->
<div class="d-none d-md-block neo-card">
    <div class="p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="px-4 py-3 border-0 fw-black" style="font-size: 13px;">Tanggal</th>
                        <th class="px-4 py-3 border-0 fw-black" style="font-size: 13px;">Jenis</th>
                        <th class="px-4 py-3 border-0 fw-black" style="font-size: 13px;">Keterangan</th>
                        <th class="px-4 py-3 border-0 fw-black" style="font-size: 13px;">Kategori</th>
                        <th class="px-4 py-3 border-0 fw-black" style="font-size: 13px;">Akun</th>
                        <th class="px-4 py-3 border-0 fw-black text-end" style="font-size: 13px;">Nominal</th>
                        <th class="px-4 py-3 border-0 fw-black text-end" style="font-size: 13px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-bottom border-3 border-dark">
                            <td class="px-4 py-3">
                                <div class="fw-black" style="font-size: 14px;">{{ $transaction->updated_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="neo-badge {{ $transaction->type === 'income' ? 'neo-badge-success' : ($transaction->type === 'expense' ? 'neo-badge-danger' : 'neo-badge-primary') }}">
                                    <i class="fas fa-{{ $transaction->type === 'income' ? 'arrow-down' : ($transaction->type === 'expense' ? 'arrow-up' : 'exchange-alt') }}"></i> {{ $transaction->type === 'income' ? 'Pemasukan' : ($transaction->type === 'expense' ? 'Pengeluaran' : 'Transfer') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="fw-bold" style="font-size: 14px;">{{ $transaction->note ?? '-' }}</div>
                                @if($transaction->type === 'transfer')
                                    <small class="text-muted" style="font-size: 11px;">
                                        Transfer: {{ $transaction->account->name }} <i class="fas fa-long-arrow-alt-right mx-1"></i> {{ $transaction->destinationAccount->name }}
                                    </small>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($transaction->category)
                                    <span class="neo-badge">
                                        <i class="{{ $transaction->category->icon }}"></i> {{ $transaction->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="neo-badge-primary neo-badge">{{ $transaction->account->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-end fw-black" style="font-size: 16px;">
                                @if($transaction->type === 'income')
                                    <span class="text-success">+ Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                @elseif($transaction->type === 'expense')
                                    <span class="text-danger">- Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-primary">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-dark fa-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item fw-bold" href="{{ route('transactions.edit', $transaction) }}">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a></li>
                                        <li>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item fw-bold text-danger">
                                                    <i class="fas fa-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-muted">
                                <i class="fas fa-inbox fa-3x opacity-50 mb-3 d-block"></i>
                                <h5 class="fw-bold mb-1" style="font-size: 16px;">Belum ada transaksi</h5>
                                <p class="mb-3" style="font-size: 13px;">Mulai catat transaksi keuanganmu!</p>
                                <a href="{{ route('transactions.create') }}" class="neo-btn neo-btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah Transaksi Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-4 border-top border-3 border-dark">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
