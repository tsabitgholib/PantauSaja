@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex align-items-center gap-3 mb-4">
    <button class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </button>
    <div class="flex-grow-1">
        <h2 class="fw-bold mb-0">Akun Keuangan</h2>
    </div>
    <a href="{{ route('accounts.create') }}" class="neo-btn neo-btn-primary">
        <i class="fas fa-plus"></i>
    </a>
</div>

<!-- Accounts List -->
<div class="row g-4">
    @forelse($accounts as $account)
        <div class="col-md-4 col-12">
            <div class="neo-card">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $account->name }}</h5>
                            <span class="neo-badge">{{ $account->type }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-dark"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item fw-bold" href="{{ route('accounts.show', $account) }}">
                                    <i class="fas fa-eye me-2"></i>Detail
                                </a></li>
                                <li><a class="dropdown-item fw-bold" href="{{ route('accounts.edit', $account) }}">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('accounts.destroy', $account) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item fw-bold text-danger" onclick="return confirm('Yakin ingin menghapus akun ini?')">
                                            <i class="fas fa-trash me-2"></i>Hapus
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-muted small mb-1">Saldo</p>
                    <h2 class="fw-bold mb-0">Rp {{ number_format($account->balance, 0, ',', '.') }}</h2>
                    @if($account->description)
                        <p class="text-muted small mt-3 mb-0">{{ $account->description }}</p>
                    @endif
                </div>
                <div class="p-4 pt-0">
                    <a href="{{ route('accounts.show', $account) }}" class="neo-btn neo-btn-primary w-100">
                        <i class="fas fa-list me-2"></i>Lihat Transaksi
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-credit-card fa-3x text-muted opacity-50 mb-3"></i>
            <h4 class="text-muted mb-3">Belum ada akun</h4>
            <p class="text-muted mb-4">Tambahkan akun keuanganmu untuk memulai</p>
            <a href="{{ route('accounts.create') }}" class="neo-btn neo-btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Akun Pertama
            </a>
        </div>
    @endforelse
</div>
@endsection
