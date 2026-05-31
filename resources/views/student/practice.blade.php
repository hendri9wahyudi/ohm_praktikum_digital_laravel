@extends('layouts.app')
@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card card-soft">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Soal Praktikum</h4>
                    <div class="small-label">Paket {{ $package->code }} | Resistor {{ $package->resistor_ohm }} Ohm</div>
                </div>
                <div class="text-end">
                    <div class="small-label">Sesi</div>
                    <div class="fw-bold">#{{ $session->id }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-soft h-100">
            <div class="card-body">
                <h5>Layout Kiri - Soal</h5>
                @foreach($questions as $question)
                    @php $saved = $answers[$question->question_no] ?? null; @endphp
                    <div class="border rounded-3 p-3 mb-3 question-row" data-question-no="{{ $question->question_no }}">
                        <div class="fw-semibold mb-2">Nomor {{ $question->question_no }}</div>
                        <div class="mb-2">{{ $question->voltage }} Volt : {{ $package->resistor_ohm }} Ohm = <span class="small-label">input jawaban</span> Ampere</div>
                        <input type="number" step="0.001" class="form-control mb-2 student-answer" placeholder="0.000" value="{{ $saved->student_answer ?? '' }}">
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary btn-verify">Next</button>
                            <button class="btn btn-sm btn-success btn-start">Start</button>
                        </div>
                        <div class="mt-2 verification small"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-soft h-100">
            <div class="card-body">
                <h5>Layout Kanan - Sensor</h5>
                @foreach($questions as $question)
                    <div class="border rounded-3 p-3 mb-3 sensor-box" data-question-no="{{ $question->question_no }}">
                        <div class="fw-semibold mb-2">Nomor {{ $question->question_no }}</div>
                        <div class="sensor-output text-muted">Tekan <strong>Start</strong> untuk membaca sensor.</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-soft h-100">
            <div class="card-body">
                <h5>Layout Bawah - Grafik & Analisis</h5>
                <canvas id="ohmChart" height="220"></canvas>
                <div class="mt-3">
                    <label class="form-label">Analisis</label>
                    <textarea id="analysisText" class="form-control" rows="5" placeholder="Tulis analisis hasil praktikum..."></textarea>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button id="processBtn" class="btn btn-warning">Prosses</button>
                    <button id="finishBtn" class="btn btn-dark">Finish</button>
                </div>
                <div id="processMsg" class="mt-3 small"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/practice.js') }}"></script>
<script>
window.practiceConfig = {
    verifyUrl: @json(route('student.verify')),
    startUrl: @json(route('student.start')),
    processUrl: @json(route('student.process')),
    finishUrl: @json(route('student.finish')),
    csrf: window.csrfToken,
};
</script>
@endpush
