@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-transparent py-3">
                <h4 class="mb-0 fw-bold">Edit Transaksi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Jenis Transaksi</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_expense" value="expense" {{ $transaction->type === 'expense' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_expense">Pengeluaran</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_income" value="income" {{ $transaction->type === 'income' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_income">Pemasukan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_transfer" value="transfer" {{ $transaction->type === 'transfer' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_transfer">Transfer</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6" id="account_field">
                            <label for="account_id" class="form-label" id="account_label">Akun / Sumber Dana</label>
                            <select class="form-select" id="account_id" name="account_id" required>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ $transaction->account_id === $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" id="to_account_field" style="display: {{ $transaction->type === 'transfer' ? 'block' : 'none' }};">
                            <label for="to_account_id" class="form-label">Tujuan Transfer</label>
                            <select class="form-select" id="to_account_id" name="to_account_id">
                                <option value="">Pilih Akun Tujuan</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ $transaction->to_account_id === $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" id="category_field" style="display: {{ $transaction->type === 'transfer' ? 'none' : 'block' }};">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-type="{{ $category->type }}" {{ $transaction->category_id === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Nominal</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ $transaction->amount }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ $transaction->date->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="note" name="note" rows="2">{{ $transaction->note }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('transactions.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function toggleFields() {
            const type = $('input[name="type"]:checked').val();
            
            if (type === 'transfer') {
                $('#category_field').hide();
                $('#to_account_field').show();
                $('#account_label').text('Dari Akun');
            } else {
                $('#to_account_field').hide();
                $('#category_field').show();
                $('#account_label').text(type === 'income' ? 'Masuk ke Akun' : 'Sumber Dana');

                $('#category_id option').each(function() {
                    const catType = $(this).data('type');
                    if (!catType || catType === type) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        }

        $('input[name="type"]').on('change', toggleFields);
        toggleFields();
    });
</script>
@endpush
