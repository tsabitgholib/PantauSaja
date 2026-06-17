@extends('layouts.app')

@section('content')
<div class="mb-5">
    <h1 class="fw-black mb-1">Halo, {{ Auth::user()->name }}! 👋</h1>
    <p class="text-muted">Ini adalah ringkasan keuanganmu hari ini</p>
</div>

<div class="d-flex gap-2 mb-5 flex-wrap">
    <a href="{{ route('transactions.create') }}?type=income" class="neo-btn neo-btn-success d-flex align-items-center gap-1 flex-grow-1 flex-md-grow-0">
        <i class="fas fa-arrow-down"></i> <span class="d-none d-md-inline">Tambah Pemasukan</span><span class="d-md-none">Pemasukan</span>
    </a>
    <a href="{{ route('transactions.create') }}?type=expense" class="neo-btn neo-btn-danger d-flex align-items-center gap-1 flex-grow-1 flex-md-grow-0">
        <i class="fas fa-arrow-up"></i> <span class="d-none d-md-inline">Tambah Pengeluaran</span><span class="d-md-none">Pengeluaran</span>
    </a>
    <a href="{{ route('accounts.index') }}" class="neo-btn neo-btn-primary d-flex align-items-center gap-1 flex-grow-1 flex-md-grow-0">
        <i class="fas fa-credit-card"></i> <span class="d-none d-md-inline">List Akun</span><span class="d-md-none">Akun</span>
    </a>
</div>

<div class="row mb-5 g-3">
    <div class="col-md-3 col-6">
        <div class="neo-card stats-card-blue h-100">
            <div class="p-3 p-md-4">
                <i class="fas fa-wallet fa-2x mb-3 opacity-75"></i>
                <p class="small fw-black mb-1 opacity-75">Total Saldo</p>
                <h4 class="fw-black mb-0">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="neo-card stats-card-green h-100">
            <div class="p-3 p-md-4">
                <i class="fas fa-arrow-down fa-2x mb-3 opacity-75"></i>
                <p class="small fw-black mb-1 opacity-75">Pemasukan Bulan Ini</p>
                <h4 class="fw-black mb-0">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="neo-card stats-card-red h-100">
            <div class="p-3 p-md-4">
                <i class="fas fa-arrow-up fa-2x mb-3 opacity-75"></i>
                <p class="small fw-black mb-1 opacity-75">Pengeluaran Bulan Ini</p>
                <h4 class="fw-black mb-0">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="neo-card stats-card-purple h-100">
            <div class="p-3 p-md-4">
                <i class="fas fa-percentage fa-2x mb-3 opacity-75"></i>
                <p class="small fw-black mb-1 opacity-75">Saving Rate</p>
                <h4 class="fw-black mb-0">{{ number_format($savingRate, 1) }}%</h4>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5 g-3">
    <div class="col-md-8 col-12">
        <div class="neo-card h-100">
            <div class="p-3 p-md-4">
                <h5 class="fw-black mb-4">Tren Arus Kas (6 Bulan Terakhir)</h5>
                <!-- Uji Coba Canvas -->
                {{-- <canvas id="testCanvas" width="400" height="200" style="border: 3px dashed blue; margin-bottom: 20px;"></canvas> --}}
                <div style="height: 300px;">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="neo-card h-100">
            <div class="p-3 p-md-4">
                <h5 class="fw-black mb-4">Pengeluaran per Kategori</h5>
                <div style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6 col-12">
        <div class="neo-card h-100">
            <div class="p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-black mb-0">Transaksi Terakhir</h5>
                    <a href="{{ route('transactions.index') }}" class="text-decoration-none fw-black" style="color:#6366f1; font-size:12px;">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    @forelse($recentTransactions as $transaction)
                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom border-3 border-dark last:border-0 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="border-3 border-dark rounded-2 d-flex align-items-center justify-content-center {{ $transaction->type === 'income' ? 'bg-green-100' : ($transaction->type === 'expense' ? 'bg-red-100' : 'bg-blue-100') }}" style="width:48px; height:48px; border-radius:12px;">
                                    <i class="fas fa-{{ $transaction->type === 'income' ? 'arrow-down' : ($transaction->type === 'expense' ? 'arrow-up' : 'exchange-alt') }} {{ $transaction->type === 'income' ? 'text-success' : ($transaction->type === 'expense' ? 'text-danger' : 'text-primary') }} fa-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="fw-black mb-1 text-truncate">{{ $transaction->note ?? ($transaction->category->name ?? ($transaction->type === 'transfer' ? 'Transfer Saldo' : 'Transaksi')) }}</p>
                                    @if($transaction->type === 'transfer')
                                        <p class="text-muted mb-1" style="font-size: 11px;">
                                            {{ $transaction->account->name }} <i class="fas fa-long-arrow-alt-right mx-1"></i> {{ $transaction->destinationAccount->name }}
                                        </p>
                                    @endif
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="neo-badge {{ $transaction->type === 'income' ? 'neo-badge-success' : ($transaction->type === 'expense' ? 'neo-badge-danger' : 'neo-badge-primary') }}">
                                            <i class="fas fa-{{ $transaction->type === 'income' ? 'arrow-down' : ($transaction->type === 'expense' ? 'arrow-up' : 'exchange-alt') }}"></i> {{ $transaction->type === 'income' ? 'Pemasukan' : ($transaction->type === 'expense' ? 'Pengeluaran' : 'Transfer') }}
                                        </span>
                                        <span class="text-muted" style="font-size:10px;">{{ $transaction->date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <p class="fw-black mb-0 {{ $transaction->type === 'income' ? 'text-success' : ($transaction->type === 'expense' ? 'text-danger' : 'text-primary') }}">
                                    {{ $transaction->type === 'income' ? '+' : ($transaction->type === 'expense' ? '-' : '') }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="fas fa-inbox fa-3x text-muted opacity-50 mb-3 d-block"></i>
                            <h5 class="fw-bold mb-1">Belum ada transaksi</h5>
                            <p class="text-muted mb-3" style="font-size:13px;">Mulai catat transaksi keuanganmu!</p>
                            <a href="{{ route('transactions.create') }}" class="neo-btn neo-btn-primary">
                                <i class="fas fa-plus me-1"></i> Tambah Transaksi Pertama
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="neo-card h-100">
            <div class="p-3 p-md-4">
                <h5 class="fw-black mb-4">Status Utang & Piutang</h5>
                <div class="mb-4 p-3 p-md-4 border-3 border-dark rounded-2" style="background:#fee2e2;">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <p class="text-muted small fw-black mb-1" style="font-size:12px;">Total Utang</p>
                            <h4 class="fw-black text-danger mb-0">Rp {{ number_format($totalDebt, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-hand-holding-usd fa-2x text-danger opacity-50 flex-shrink-0"></i>
                    </div>
                </div>
                <div class="mb-4 p-3 p-md-4 border-3 border-dark rounded-2" style="background:#dcfce7;">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <p class="text-muted small fw-black mb-1" style="font-size:12px;">Total Piutang</p>
                            <h4 class="fw-black text-success mb-0">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-donate fa-2x text-success opacity-50 flex-shrink-0"></i>
                    </div>
                </div>
                <a href="{{ route('debts.index') }}" class="neo-btn neo-btn-primary w-100">
                    <i class="fas fa-cog me-1"></i> Kelola Utang & Piutang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    function init() {
        console.log('=== INIT CHARTS ===');
        
        // Test Canvas
        // const testCanvas = document.getElementById('testCanvas');
        // if (testCanvas) {
        //     const testCtx = testCanvas.getContext('2d');
        //     testCtx.fillStyle = '#6366f1';
        //     testCtx.fillRect(0, 0, testCanvas.width, testCanvas.height);
        //     testCtx.fillStyle = '#fff';
        //     testCtx.font = 'bold 20px Outfit';
        //     testCtx.fillText('CANVAS BERFUNGSI!', 50, 100);
        // }
        
        // Cash Flow
        const cfCanvas = document.getElementById('cashFlowChart');
        if (cfCanvas) {
            console.log('Cash Flow Canvas Found!');
            const ctx = cfCanvas.getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($cashFlowData['labels']) !!},
                    datasets: [
                        { label: 'Pemasukan', data: {!! json_encode($cashFlowData['income']) !!}, backgroundColor: '#22c55e', borderColor: '#000', borderWidth: 3 },
                        { label: 'Pengeluaran', data: {!! json_encode($cashFlowData['expense']) !!}, backgroundColor: '#ef4444', borderColor: '#000', borderWidth: 3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        
        // Category
        const catCanvas = document.getElementById('categoryChart');
        if (catCanvas) {
            console.log('Category Canvas Found!');
            const ctx2 = catCanvas.getContext('2d');
            
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($categoryChart['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($categoryChart['data']) !!},
                        backgroundColor: ['#6366f1','#22c55e','#ef4444','#f59e0b','#8b5cf6','#ec4899','#06b6d4','#84cc16']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('load', init);
})();
</script>
@endpush
