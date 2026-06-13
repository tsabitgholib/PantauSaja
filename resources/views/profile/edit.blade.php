@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="fw-bold mb-4">Pengaturan Profil</h2>

        <!-- Profile Information -->
        <div class="card mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0 fw-bold">Informasi Profil</h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">Simpan Profil</button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-success small">Berhasil disimpan.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Update -->
        <div class="card mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0 fw-bold">Ubah Password</h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">Simpan Password</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-success small">Berhasil disimpan.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Database Backup -->
        <div class="card mb-4">
            <div class="card-header bg-transparent py-3 text-white bg-dark">
                <h5 class="mb-0 fw-bold">Backup Data</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Unduh seluruh data keuangan Anda dalam format SQL/SQLite untuk cadangan pribadi.</p>
                <a href="{{ route('backup.download') }}" class="btn btn-dark">
                    <i class="fas fa-download me-2"></i>Download Backup Database
                </a>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card border-danger mb-5">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0 fw-bold">Hapus Akun</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.</p>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    Hapus Akun Permanen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun? Masukkan password Anda untuk konfirmasi.</p>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
