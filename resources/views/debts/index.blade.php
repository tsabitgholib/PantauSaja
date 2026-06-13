@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Utang & Piutang</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDebtModal">
        <i class="fas fa-plus me-2"></i>Tambah Catatan
    </button>
</div>

<div class="row">
    <!-- Utang (Debt) -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0 fw-bold text-danger">Utang (Saya Pinjam)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debts as $debt)
                                <tr class="{{ $debt->status === 'paid' ? 'opacity-50' : '' }}">
                                    <td class="ps-3">
                                        <div class="fw-bold">{{ $debt->person_name }}</div>
                                        @if($debt->due_date)
                                            <small class="text-muted">Tempo: {{ $debt->due_date->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        Rp {{ number_format($debt->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($debt->status === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <form action="{{ route('debts.update', $debt) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="toggle_status" value="1">
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none" title="Ubah Status">
                                                <i class="fas {{ $debt->status === 'paid' ? 'fa-undo' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('debts.destroy', $debt) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Hapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-danger text-decoration-none">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada catatan utang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Piutang (Receivable) -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0 fw-bold text-success">Piutang (Orang Pinjam)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receivables as $debt)
                                <tr class="{{ $debt->status === 'paid' ? 'opacity-50' : '' }}">
                                    <td class="ps-3">
                                        <div class="fw-bold">{{ $debt->person_name }}</div>
                                        @if($debt->due_date)
                                            <small class="text-muted">Tempo: {{ $debt->due_date->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($debt->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($debt->status === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <form action="{{ route('debts.update', $debt) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="toggle_status" value="1">
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none" title="Ubah Status">
                                                <i class="fas {{ $debt->status === 'paid' ? 'fa-undo' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('debts.destroy', $debt) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Hapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-danger text-decoration-none">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada catatan piutang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDebtModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('debts.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <select name="type" class="form-select" required>
                            <option value="debt">Utang (Saya Pinjam)</option>
                            <option value="receivable">Piutang (Orang Pinjam)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Orang</label>
                        <input type="text" name="person_name" class="form-control" placeholder="Contoh: Andi, Budi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jatuh Tempo (Opsional)</label>
                        <input type="date" name="due_date" class="form-control">
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
