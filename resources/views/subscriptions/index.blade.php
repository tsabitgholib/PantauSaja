@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Langganan Rutin</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal">
        <i class="fas fa-plus me-2"></i>Tambah Langganan
    </button>
</div>

<div class="row">
    @forelse($subscriptions as $sub)
        <div class="col-md-4 mb-4">
            <div class="card h-100 {{ $sub->status === 'inactive' ? 'opacity-75' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title fw-bold mb-0">{{ $sub->name }}</h5>
                            <span class="badge {{ $sub->status === 'active' ? 'bg-success' : 'bg-secondary' }} mt-2">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('subscriptions.update', $sub) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="toggle_status" value="1">
                                        <button type="submit" class="dropdown-item">
                                            {{ $sub->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('subscriptions.destroy', $sub) }}" method="POST" onsubmit="return confirm('Hapus langganan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h4 class="fw-bold text-primary mb-3">Rp {{ number_format($sub->amount, 0, ',', '.') }}<small class="text-muted fs-6">/bulan</small></h4>
                    
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-calendar-day me-2"></i>
                        Setiap tanggal {{ $sub->billing_date }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <p>Belum ada langganan rutin yang dicatat.</p>
        </div>
    @endforelse
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSubscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('subscriptions.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Langganan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Netflix, Spotify, Internet" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya Bulanan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Penagihan (1-31)</label>
                        <input type="number" name="billing_date" class="form-control" min="1" max="31" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
