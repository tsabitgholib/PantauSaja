@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-transparent py-3">
                <h4 class="mb-0 fw-bold">Catat Transaksi Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Jenis Transaksi</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_expense" value="expense" checked>
                                    <label class="form-check-label" for="type_expense">Pengeluaran</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_income" value="income">
                                    <label class="form-check-label" for="type_income">Pemasukan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_transfer" value="transfer">
                                    <label class="form-check-label" for="type_transfer">Transfer</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6" id="account_field">
                            <label for="account_id" class="form-label" id="account_label">Akun / Sumber Dana</label>
                            <select class="form-select @error('account_id') is-invalid @enderror" id="account_id" name="account_id" required>
                                <option value="">Pilih Akun</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} (Rp {{ number_format($account->balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="to_account_field" style="display: none;">
                            <label for="to_account_id" class="form-label">Tujuan Transfer</label>
                            <select class="form-select @error('to_account_id') is-invalid @enderror" id="to_account_id" name="to_account_id">
                                <option value="">Pilih Akun Tujuan</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                            @error('to_account_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6" id="category_field">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-type="{{ $category->type }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Nominal</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="2" placeholder="Tulis catatan di sini...">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('transactions.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeRadios = document.querySelectorAll('input[name="type"]');
        const categoryField = document.getElementById('category_field');
        const categorySelect = document.getElementById('category_id');
        const toAccountField = document.getElementById('to_account_field');
        const toAccountSelect = document.getElementById('to_account_id');
        const accountLabel = document.getElementById('account_label');

        function toggleFields() {
            const selectedType = document.querySelector('input[name="type"]:checked').value;
            
            if (selectedType === 'transfer') {
                categoryField.style.display = 'none';
                categorySelect.required = false;
                toAccountField.style.display = 'block';
                toAccountSelect.required = true;
                accountLabel.textContent = 'Dari Akun';
            } else {
                toAccountField.style.display = 'none';
                toAccountSelect.required = false;
                categoryField.style.display = 'block';
                categorySelect.required = true;
                accountLabel.textContent = selectedType === 'income' ? 'Masuk ke Akun' : 'Sumber Dana';

                // Filter categories based on type
                Array.from(categorySelect.options).forEach(option => {
                    const catType = option.getAttribute('data-type');
                    if (!catType || catType === selectedType) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                if (categorySelect.selectedOptions[0] && categorySelect.selectedOptions[0].style.display === 'none') {
                    categorySelect.value = '';
                }
            }
        }

        typeRadios.forEach(radio => {
            radio.addEventListener('change', toggleFields);
        });

        toggleFields(); // Initial call
    });
</script>
@endpush
