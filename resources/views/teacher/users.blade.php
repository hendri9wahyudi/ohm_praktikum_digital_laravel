@extends('layouts.app')
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card card-soft">
            <div class="card-body">
                <h5>Tambah User</h5>
                <form method="POST" action="{{ route('teacher.users.store') }}">
                    @csrf
                    <div class="mb-2"><input class="form-control" name="nis" placeholder="NIS (opsional)"></div>
                    <div class="mb-2"><input class="form-control" name="name" placeholder="Nama" required></div>
                    <div class="mb-2"><input class="form-control" name="username" placeholder="Username" required></div>
                    <div class="mb-2"><input class="form-control" name="password" placeholder="Password" required></div>
                    <div class="mb-2">
                        <select class="form-select" name="role" required>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card card-soft mb-3">
            <div class="card-body">
                <form class="d-flex gap-2" method="GET">
                    <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Cari nis / nama / username...">
                    <button class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
        <div class="card card-soft">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <form method="POST" action="{{ route('teacher.users.update', $user) }}" id="update-{{ $user->id }}">
                                @csrf
                                <td><input class="form-control form-control-sm" name="nis" value="{{ $user->nis }}"></td>
                                <td><input class="form-control form-control-sm" name="name" value="{{ $user->name }}"></td>
                                <td><input class="form-control form-control-sm" name="username" value="{{ $user->username }}"></td>
                                <td>
                                    <select class="form-select form-select-sm" name="role">
                                        <option value="siswa" {{ $user->role === 'siswa' ? 'selected' : '' }}>Siswa</option>
                                        <option value="guru" {{ $user->role === 'guru' ? 'selected' : '' }}>Guru</option>
                                    </select>
                                    <input class="form-control form-control-sm mt-2" name="password" placeholder="Password baru (opsional)">
                                </td>
                                <td class="d-flex gap-2">
                                    <button class="btn btn-sm btn-success">Simpan</button>
                                    <button class="btn btn-sm btn-danger" formaction="{{ route('teacher.users.delete', $user) }}" onclick="return confirm('Hapus user ini?')">Hapus</button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
