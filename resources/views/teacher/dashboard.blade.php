@extends('layouts.app')
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card card-soft"><div class="card-body"><div class="small-label">Total Siswa</div><h2>{{ $totalStudents }}</h2></div></div>
    </div>
    <div class="col-md-4">
        <div class="card card-soft"><div class="card-body"><div class="small-label">Praktikum Selesai</div><h2>{{ $finished }}</h2></div></div>
    </div>
    <div class="col-md-4">
        <div class="card card-soft"><div class="card-body"><div class="small-label">Rata-rata Nilai</div><h2>{{ $avgScore }}</h2></div></div>
    </div>
</div>
@endsection
