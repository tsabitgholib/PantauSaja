@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-transparent py-3">
                <h4 class="mb-0 fw-bold">Edit Akun</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('accounts.update', $account) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Akun</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $account->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Akun</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="Bank" {{ old('type', $account->type) == 'Bank' ? 'selected' : '' }}>Bank</option>
                            <option value="E-Wallet" {{ old('type', $account->type) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="Cash" {{ old('type', $account->type) == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Investasi" {{ old('type', $account->type) == 'Investasi' ? 'selected' : '' }}>Investasi</option>
                            <option value="Lainnya" {{ old('type', $account->type) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="balance" class="form-label">Saldo Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('balance') is-invalid @enderror" id="balance" name="balance" value="{{ old('balance', $account->balance) }}" required>
                        </div>
                        <small class="text-muted">Mengubah saldo secara manual tidak akan mencatat transaksi baru.</small>
                        @error('balance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">Warna Label</label>
                        <input type="color" class="form-control form-control-color w-100" id="color" name="color" value="{{ old('color', $account->color) }}">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('accounts.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
