@extends('layouts.app')
@section('content')
<div class="card card-soft">
    <div class="card-body">
        <h4 class="mb-2">Dashboard Siswa</h4>
        <p class="mb-0">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Paket praktikum aktif: <strong>{{ $package->code }}</strong> ({{ $package->resistor_ohm }} Ohm).</p>
    </div>
</div>
@endsection
