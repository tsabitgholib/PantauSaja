@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="d-flex align-items-center gap-3 mb-4">
    <button class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </button>
    <h2 class="fw-bold mb-0">Tambah Akun Baru</h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="neo-card">
            <div class="p-4">
                <form action="{{ route('accounts.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Nama Akun</label>
                        <input type="text" class="neo-input form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: BCA, Dompet Tunai" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="form-label fw-bold">Jenis Akun</label>
                        <select class="neo-select form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Bank" {{ old('type') == 'Bank' ? 'selected' : '' }}>Bank</option>
                            <option value="E-Wallet" {{ old('type') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="Cash" {{ old('type') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Investasi" {{ old('type') == 'Investasi' ? 'selected' : '' }}>Investasi</option>
                            <option value="Lainnya" {{ old('type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="balance" class="form-label fw-bold">Saldo Awal</label>
                        <div class="input-group">
                            <span class="input-group-text border-3 border-dark" style="border-radius:0;">Rp</span>
                            <input type="number" class="neo-input form-control @error('balance') is-invalid @enderror" id="balance" name="balance" value="{{ old('balance', 0) }}" required>
                        </div>
                        @error('balance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="color" class="form-label fw-bold">Warna Label</label>
                        <input type="color" class="neo-input form-control form-control-color w-100" id="color" name="color" value="{{ old('color', '#6366f1') }}" style="height: 50px;">
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('accounts.index') }}" class="neo-btn w-100">Batal</a>
                        <button type="submit" class="neo-btn neo-btn-primary w-100">Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
