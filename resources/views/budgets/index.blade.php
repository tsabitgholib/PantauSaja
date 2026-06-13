@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <button class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </button>
    <div class="flex-grow-1">
        <h2 class="fw-bold mb-0">Budgeting Bulanan</h2>
    </div>
    <button type="button" class="neo-btn neo-btn-primary d-flex align-items-center gap-1" id="openBudgetModalBtn">
        <i class="fas fa-plus"></i>
        <span class="d-none d-md-inline">Tambah</span>
    </button>
</div>

<div class="row g-3">
    @forelse($budgets as $budget)
        <div class="col-md-6 col-12">
            <div class="neo-card">
                <div class="p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="{{ $budget->category->icon }} me-2" style="color: #6366f1;"></i>
                            {{ $budget->category->name }}
                        </h5>
                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Hapus budget ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-bold">Terpakai: Rp {{ number_format($budget->used, 0, ',', '.') }}</span>
                        <span class="text-muted small fw-bold">Limit: Rp {{ number_format($budget->amount, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $colorClass = 'bg-success';
                        if ($budget->percentage >= 100) $colorClass = 'bg-danger';
                        elseif ($budget->percentage >= 80) $colorClass = 'bg-warning';
                    @endphp
                    <div class="progress mb-3" style="height:14px; border:3px solid #0f172a; border-radius:8px;">
                        <div class="progress-bar {{ $colorClass }}" role="progressbar" style="width:{{ min($budget->percentage,100) }}%" aria-valuenow="{{ $budget->percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold small">{{ number_format($budget->percentage, 1) }}%</span>
                        <span class="small fw-bold {{ $budget->amount - $budget->used < 0 ? 'text-danger' : 'text-muted' }}">
                            Sisa: Rp {{ number_format($budget->amount - $budget->used, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-wallet fa-3x text-muted opacity-50 mb-3"></i>
            <p class="text-muted mb-4">Belum ada budget yang diatur untuk bulan ini</p>
            <button type="button" class="neo-btn neo-btn-primary" id="openBudgetModalBtn2">
                <i class="fas fa-plus me-2"></i>Atur Budget Pertama
            </button>
        </div>
    @endforelse
</div>

<!-- Updated Modal Structure -->
<div class="dompet-backdrop" id="budgetModalBackdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1040;"></div>
<div class="dompet-modal" id="addBudgetModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1050; overflow-x:hidden; overflow-y:auto; pointer-events:none;">
    <div class="modal-dialog modal-dialog-centered" style="pointer-events:auto; margin: 1.75rem auto; max-width: 500px;">
        <div class="neo-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Atur Budget Kategori</h5>
                <button type="button" class="back-btn" id="closeBudgetModalBtn" style="pointer-events:auto;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('budgets.store') }}" method="POST" id="budgetForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori Pengeluaran</label>
                    <select name="category_id" class="neo-select form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Limit Bulanan</label>
                    <div class="input-group">
                        <span class="input-group-text border-3 border-dark" style="border-radius:8px 0 0 8px;">Rp</span>
                        <input type="number" name="amount" class="neo-input form-control" style="border-left:none; border-radius:0 8px 8px 0;" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="neo-btn flex-grow-1" id="cancelBudgetModalBtn" style="pointer-events:auto;">Batal</button>
                    <button type="submit" class="neo-btn neo-btn-primary flex-grow-1" style="pointer-events:auto;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const budgetModal = document.getElementById('addBudgetModal');
    const budgetModalBackdrop = document.getElementById('budgetModalBackdrop');
    const openBtns = [document.getElementById('openBudgetModalBtn'), document.getElementById('openBudgetModalBtn2')].filter(b => b);
    const closeBtns = [document.getElementById('closeBudgetModalBtn'), document.getElementById('cancelBudgetModalBtn')].filter(b => b);
    
    openBtns.forEach(btn => btn.addEventListener('click', function() {
        budgetModalBackdrop.style.display = 'block';
        budgetModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }));

    function closeModal() {
        budgetModalBackdrop.style.display = 'none';
        budgetModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    budgetModalBackdrop.addEventListener('click', closeModal);
});
</script>
@endsection
