@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <button class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </button>
    <div class="flex-grow-1">
        <h2 class="fw-bold mb-0">Target Keuangan</h2>
    </div>
    <button type="button" class="neo-btn neo-btn-primary d-flex align-items-center gap-1" id="openTargetModalBtn">
        <i class="fas fa-plus"></i>
        <span class="d-none d-md-inline">Tambah</span>
    </button>
</div>

<div class="row g-3">
    @forelse($targets as $target)
        @php
            $percentage = ($target->target_amount > 0) ? ($target->current_amount / $target->target_amount) * 100 : 0;
            $remaining = $target->target_amount - $target->current_amount;
        @endphp
        <div class="col-md-6 col-12">
            <div class="neo-card">
                <div class="p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">{{ $target->name }}</h5>
                        <form action="{{ route('targets.destroy', $target) }}" method="POST" onsubmit="return confirm('Hapus target ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-bold">Terkumpul: Rp {{ number_format($target->current_amount, 0, ',', '.') }}</span>
                        <span class="text-muted small fw-bold">Target: Rp {{ number_format($target->target_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="progress mb-3" style="height:14px; border:3px solid #0f172a; border-radius:8px;">
                        <div class="progress-bar {{ $percentage >= 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width:{{ min($percentage, 100) }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold small">{{ number_format($percentage, 1) }}%</span>
                        <button type="button" class="neo-btn" style="padding: 6px 12px; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#updateProgressModal{{ $target->id }}">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Progress Modal -->
        <div class="modal fade" id="updateProgressModal{{ $target->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="neo-card p-4">
                    <form action="{{ route('targets.update', $target) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <h5 class="fw-bold mb-3">Tambah Tabungan: {{ $target->name }}</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nominal Tambahan</label>
                            <div class="input-group">
                                <span class="input-group-text border-3 border-dark" style="border-radius:8px 0 0 8px;">Rp</span>
                                <input type="number" name="add_amount" class="neo-input form-control" required>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="neo-btn flex-grow-1" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="neo-btn neo-btn-primary flex-grow-1">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-bullseye fa-3x text-muted opacity-50 mb-3"></i>
            <p class="text-muted mb-4">Belum ada target keuangan</p>
            <button type="button" class="neo-btn neo-btn-primary" id="openTargetModalBtn2">
                <i class="fas fa-plus me-2"></i>Buat Target Pertama
            </button>
        </div>
    @endforelse
</div>

<!-- Add Modal -->
<div class="dompet-backdrop" id="targetModalBackdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1040;"></div>
<div class="dompet-modal" id="addTargetModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050; overflow-x:hidden; overflow-y:auto; pointer-events:none;">
    <div class="modal-dialog modal-dialog-centered" style="pointer-events:auto; margin: 1.75rem auto; max-width: 500px;">
        <div class="neo-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Tambah Target Baru</h5>
                <button type="button" class="back-btn" id="closeTargetModalBtn" style="pointer-events:auto;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('targets.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Target</label>
                    <input type="text" name="name" class="neo-input" placeholder="Contoh: Dana Darurat" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nominal Target</label>
                    <div class="input-group">
                        <span class="input-group-text border-3 border-dark" style="border-radius:8px 0 0 8px;">Rp</span>
                        <input type="number" name="target_amount" class="neo-input form-control" style="border-left:none; border-radius:0 8px 8px 0;" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Saldo Awal</label>
                    <div class="input-group">
                        <span class="input-group-text border-3 border-dark" style="border-radius:8px 0 0 8px;">Rp</span>
                        <input type="number" name="current_amount" class="neo-input form-control" style="border-left:none; border-radius:0 8px 8px 0;" value="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Target Tanggal</label>
                    <input type="date" name="target_date" class="neo-input">
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="neo-btn flex-grow-1" id="cancelTargetModalBtn" style="pointer-events:auto;">Batal</button>
                    <button type="submit" class="neo-btn neo-btn-primary flex-grow-1" style="pointer-events:auto;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetModal = document.getElementById('addTargetModal');
    const targetModalBackdrop = document.getElementById('targetModalBackdrop');
    const openBtns = [document.getElementById('openTargetModalBtn'), document.getElementById('openTargetModalBtn2')].filter(b => b);
    const closeBtns = [document.getElementById('closeTargetModalBtn'), document.getElementById('cancelTargetModalBtn')].filter(b => b);
    
    openBtns.forEach(btn => btn.addEventListener('click', function() {
        targetModalBackdrop.style.display = 'block';
        targetModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }));

    function closeModal() {
        targetModalBackdrop.style.display = 'none';
        targetModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    targetModalBackdrop.addEventListener('click', closeModal);
});
</script>
@endsection
