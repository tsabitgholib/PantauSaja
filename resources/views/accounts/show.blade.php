@extends('layouts.app')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Akun</a></li>
            <li class="breadcrumb-item active">{{ $account->name }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0">{{ $account->name }}</h2>
        <div>
            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Edit Akun
            </a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100 border-start border-4" style="border-left-color: {{ $account->color ?? '#0d6efd' }} !important;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Saldo Saat Ini</h6>
                <h2 class="fw-bold mb-0">Rp {{ number_format($account->balance, 0, ',', '.') }}</h2>
                <small class="text-muted">{{ $account->type }}</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent py-3">
        <h5 class="mb-0 fw-bold">Riwayat Transaksi</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Keterangan</th>
                        <th>Kategori</th>
                        <th class="text-end pe-4">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="ps-4">{{ $transaction->date->format('d M Y') }}</td>
                            <td>
                                {{ $transaction->note ?? '-' }}
                                @if($transaction->type === 'transfer')
                                    <br>
                                    <small class="text-muted">
                                        @if($transaction->account_id === $account->id)
                                            Ke: {{ $transaction->destinationAccount->name }}
                                        @else
                                            Dari: {{ $transaction->account->name }}
                                        @endif
                                    </small>
                                @endif
                            </td>
                            <td>{{ $transaction->category->name ?? '-' }}</td>
                            <td class="text-end pe-4 fw-bold">
                                @if($transaction->type === 'income' || ($transaction->type === 'transfer' && $transaction->to_account_id === $account->id))
                                    <span class="text-success">+ Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-danger">- Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi di akun ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
