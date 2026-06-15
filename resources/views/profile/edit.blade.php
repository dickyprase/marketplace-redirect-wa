@extends('layouts.admin')
@section('title', 'Profile')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Profile</h4>
    <p class="text-muted small mb-0">Kelola informasi akun Anda</p>
</div>

<div class="vstack gap-4" style="max-width:640px">
    {{-- Profile Info --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-1">Informasi Profile</h6>
            <p class="text-muted small mb-3">Update nama dan email Anda.</p>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
                <div class="vstack gap-3">
                    <div>
                        <label for="name" class="form-label fw-medium">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-control">
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="form-control">
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-success small" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,2000)">Tersimpan ✓</span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Password --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-1">Ubah Password</h6>
            <p class="text-muted small mb-3">Pastikan akun Anda menggunakan password yang kuat.</p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('PUT')
                <div class="vstack gap-3">
                    <div>
                        <label for="current_password" class="form-label fw-medium">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="form-control">
                        @error('current_password', 'updatePassword')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="password" class="form-label fw-medium">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-control">
                        @error('password', 'updatePassword')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-success small" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,2000)">Tersimpan ✓</span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="card border-0 shadow-sm border-top border-danger">
        <div class="card-body p-4">
            <h6 class="fw-bold text-danger mb-1">Hapus Akun</h6>
            <p class="text-muted small mb-3">Setelah dihapus, semua data akan hilang permanen.</p>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">Hapus Akun</button>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Akun?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Semua data akan dihapus permanen. Masukkan password untuk konfirmasi.</p>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    @error('password', 'userDeletion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
