@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <button class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </button>
    <div class="flex-grow-1">
        <h2 class="fw-black mb-0" style="font-size: clamp(20px, 4vw, 28px);">Utang & Piutang</h2>
    </div>
    <button type="button" class="neo-btn neo-btn-primary d-flex align-items-center gap-1 py-2 px-3" id="openDebtModalBtn">
        <i class="fas fa-plus"></i>
        <span class="d-none d-md-inline">Tambah Catatan</span>
    </button>
</div>

<div class="row g-4">
    <!-- Utang (Debt) -->
    <div class="col-md-6">
        <div class="neo-card h-100">
            <div class="p-3 p-md-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="bg-red-100 border-3 border-dark rounded-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 10px;">
                        <i class="fas fa-hand-holding-usd text-danger"></i>
                    </div>
                    <h5 class="mb-0 fw-black text-danger">Utang (Saya Pinjam)</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="border-bottom border-3 border-dark">
                                <th class="py-2 border-0 fw-black" style="font-size: 13px;">Nama</th>
                                <th class="py-2 border-0 fw-black text-end" style="font-size: 13px;">Nominal</th>
                                <th class="py-2 border-0 fw-black text-center" style="font-size: 13px;">Status</th>
                                <th class="py-2 border-0 fw-black text-end" style="font-size: 13px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debts as $debt)
                                <tr class="border-bottom border-2 border-dark {{ $debt->status === 'paid' ? 'opacity-50' : '' }}">
                                    <td class="py-3">
                                        <div class="fw-bold" style="font-size: 14px;">{{ $debt->person_name }}</div>
                                        @if($debt->due_date)
                                            <div class="text-muted" style="font-size: 11px;">Tempo: {{ $debt->due_date->format('d M Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end fw-black text-danger" style="font-size: 14px;">
                                        Rp {{ number_format($debt->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="neo-badge {{ $debt->status === 'paid' ? 'neo-badge-success' : 'bg-warning border-dark' }}" style="font-size: 10px;">
                                            {{ $debt->status === 'paid' ? 'Lunas' : 'Belum' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v text-dark"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow border-3 border-dark">
                                                <li>
                                                    @if($debt->status === 'paid')
                                                        <form action="{{ route('debts.update', $debt) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="toggle_status" value="1">
                                                            <button type="submit" class="dropdown-item fw-bold">
                                                                <i class="fas fa-undo me-2"></i> Set Belum Lunas
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button type="button" class="dropdown-item fw-bold" data-bs-toggle="modal" data-bs-target="#payDebtModal{{ $debt->id }}">
                                                            <i class="fas fa-check me-2"></i> Set Lunas
                                                        </button>
                                                    @endif
                                                </li>
                                                <li>
                                                    <form action="{{ route('debts.destroy', $debt) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item fw-bold text-danger">
                                                            <i class="fas fa-trash me-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                        @if($debt->status !== 'paid')
                                        <!-- Modal Pelunasan -->
                                        <div class="modal fade" id="payDebtModal{{ $debt->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <div class="neo-card p-4 w-100 mx-3 mx-md-0">
                                                    <form action="{{ route('debts.update', $debt) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="toggle_status" value="1">
                                                        <h5 class="fw-black mb-3">Pelunasan: {{ $debt->person_name }}</h5>
                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold small">Pilih Akun untuk {{ $debt->type === 'debt' ? 'Membayar' : 'Menerima Uang' }}</label>
                                                            <select name="account_id" class="neo-select form-select" required>
                                                                <option value="">Pilih Akun</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}">{{ $account->name }} (Rp {{ number_format($account->balance, 0, ',', '.') }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="neo-btn flex-grow-1" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="neo-btn neo-btn-primary flex-grow-1">Konfirmasi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Tidak ada catatan utang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Piutang (Receivable) -->
    <div class="col-md-6">
        <div class="neo-card h-100">
            <div class="p-3 p-md-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="bg-green-100 border-3 border-dark rounded-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 10px;">
                        <i class="fas fa-donate text-success"></i>
                    </div>
                    <h5 class="mb-0 fw-black text-success">Piutang (Orang Pinjam)</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="border-bottom border-3 border-dark">
                                <th class="py-2 border-0 fw-black" style="font-size: 13px;">Nama</th>
                                <th class="py-2 border-0 fw-black text-end" style="font-size: 13px;">Nominal</th>
                                <th class="py-2 border-0 fw-black text-center" style="font-size: 13px;">Status</th>
                                <th class="py-2 border-0 fw-black text-end" style="font-size: 13px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receivables as $debt)
                                <tr class="border-bottom border-2 border-dark {{ $debt->status === 'paid' ? 'opacity-50' : '' }}">
                                    <td class="py-3">
                                        <div class="fw-bold" style="font-size: 14px;">{{ $debt->person_name }}</div>
                                        @if($debt->due_date)
                                            <div class="text-muted" style="font-size: 11px;">Tempo: {{ $debt->due_date->format('d M Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end fw-black text-success" style="font-size: 14px;">
                                        Rp {{ number_format($debt->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="neo-badge {{ $debt->status === 'paid' ? 'neo-badge-success' : 'bg-warning border-dark' }}" style="font-size: 10px;">
                                            {{ $debt->status === 'paid' ? 'Lunas' : 'Belum' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v text-dark"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow border-3 border-dark">
                                                <li>
                                                    @if($debt->status === 'paid')
                                                        <form action="{{ route('debts.update', $debt) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="toggle_status" value="1">
                                                            <button type="submit" class="dropdown-item fw-bold">
                                                                <i class="fas fa-undo me-2"></i> Set Belum Lunas
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button type="button" class="dropdown-item fw-bold" data-bs-toggle="modal" data-bs-target="#payDebtModal{{ $debt->id }}">
                                                            <i class="fas fa-check me-2"></i> Set Lunas
                                                        </button>
                                                    @endif
                                                </li>
                                                <li>
                                                    <form action="{{ route('debts.destroy', $debt) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item fw-bold text-danger">
                                                            <i class="fas fa-trash me-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                        @if($debt->status !== 'paid')
                                        <!-- Modal Pelunasan -->
                                        <div class="modal fade" id="payDebtModal{{ $debt->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <div class="neo-card p-4 w-100 mx-3 mx-md-0">
                                                    <form action="{{ route('debts.update', $debt) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="toggle_status" value="1">
                                                        <h5 class="fw-black mb-3">Pelunasan: {{ $debt->person_name }}</h5>
                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold small">Pilih Akun untuk {{ $debt->type === 'debt' ? 'Membayar' : 'Menerima Uang' }}</label>
                                                            <select name="account_id" class="neo-select form-select" required>
                                                                <option value="">Pilih Akun</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}">{{ $account->name }} (Rp {{ number_format($account->balance, 0, ',', '.') }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="neo-btn flex-grow-1" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="neo-btn neo-btn-primary flex-grow-1">Konfirmasi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Tidak ada catatan piutang.</td>
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
<div class="dompet-backdrop" id="debtModalBackdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1040;"></div>
<div class="dompet-modal" id="addDebtModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050; overflow-x:hidden; overflow-y:auto; pointer-events:none;">
    <div class="modal-dialog modal-dialog-centered" style="pointer-events:auto; margin: 1.75rem auto; max-width: 500px;">
        <div class="neo-card p-4 w-100 mx-3 mx-md-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-black mb-0">Tambah Catatan Baru</h5>
                <button type="button" class="back-btn" id="closeDebtModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('debts.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Jenis</label>
                    <select name="type" class="neo-select form-select" required>
                        <option value="debt">Utang (Saya Pinjam)</option>
                        <option value="receivable">Piutang (Orang Pinjam)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Pilih Akun (Untuk Potong/Tambah Saldo)</label>
                    <select name="account_id" class="neo-select form-select" required>
                        <option value="">Pilih Akun</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} (Rp {{ number_format($account->balance, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Nama Orang</label>
                    <input type="text" name="person_name" class="neo-input" placeholder="Contoh: Andi, Budi" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Nominal</label>
                    <div class="input-group">
                        <span class="input-group-text border-3 border-dark" style="border-radius:12px 0 0 12px; background: white; font-weight: 800;">Rp</span>
                        <input type="number" name="amount" class="neo-input form-control" style="border-left:none; border-radius:0 12px 12px 0;" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small">Jatuh Tempo (Opsional)</label>
                    <input type="date" name="due_date" class="neo-input">
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="neo-btn flex-grow-1" id="cancelDebtModalBtn">Batal</button>
                    <button type="submit" class="neo-btn neo-btn-primary flex-grow-1">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const debtModal = document.getElementById('addDebtModal');
    const debtModalBackdrop = document.getElementById('debtModalBackdrop');
    const openBtn = document.getElementById('openDebtModalBtn');
    const closeBtns = [document.getElementById('closeDebtModalBtn'), document.getElementById('cancelDebtModalBtn')];
    
    if(openBtn) {
        openBtn.addEventListener('click', function() {
            debtModalBackdrop.style.display = 'block';
            debtModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    }

    function closeModal() {
        debtModalBackdrop.style.display = 'none';
        debtModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    closeBtns.forEach(btn => {
        if(btn) btn.addEventListener('click', closeModal);
    });
    
    if(debtModalBackdrop) {
        debtModalBackdrop.addEventListener('click', closeModal);
    }
});
</script>
@endsection
