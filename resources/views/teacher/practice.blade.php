@extends('layouts.app')
@section('content')
<div class="card card-soft mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
        <form class="d-flex gap-2" method="GET">
            <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Cari nis / nama...">
            <button class="btn btn-primary">Search</button>
        </form>
        <div class="small-label">Data siswa praktikum</div>
    </div>
</div>

<form method="POST" action="{{ route('teacher.practice.save') }}">
    @csrf
    <div class="card card-soft">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kehadiran</th>
                        <th>Nilai Soal</th>
                        <th>Nilai Praktikum</th>
                        <th>Total Nilai</th>
                        <th>Paket</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sessions as $session)
                    <tr>
                        <td>{{ $session->user->nis }}</td>
                        <td>{{ $session->user->name }}</td>
                        <td>
                            <input type="checkbox" name="records[{{ $session->id }}][attendance]" value="1" {{ $session->attendance ? 'checked' : '' }}>
                            <input type="hidden" name="records[{{ $session->id }}][session_id]" value="{{ $session->id }}">
                        </td>
                        <td>{{ $session->quiz_score }}</td>
                        <td>{{ $session->practical_score }}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="records[{{ $session->id }}][total_score]" value="{{ $session->total_score }}" min="0" max="100">
                        </td>
                        <td>{{ $session->package->code ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-success">Save</button>
        </div>
    </div>
</form>
@endsection
