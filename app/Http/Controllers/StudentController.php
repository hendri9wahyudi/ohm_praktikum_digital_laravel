<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PracticeAnswer;
use App\Models\PracticeSession;
use App\Models\Question;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    public function __construct()
    {
        if (auth()->check() && auth()->user()->role !== 'siswa') {
            abort(403);
        }
    }

    private function currentPackage(): Package
    {
        $code = session('practice_package');

        if (! $code) {
            $code = collect(['A', 'B', 'C', 'D'])->random();
            session(['practice_package' => $code]);
        }

        return Package::with('questions')->where('code', $code)->firstOrFail();
    }

    private function currentSession(): PracticeSession
    {
        $package = $this->currentPackage();

        return PracticeSession::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'package_id' => $package->id,
            ],
            [
                'attendance' => true,
                'quiz_score' => 0,
                'practical_score' => 0,
                'manual_total_score' => 0,
                'total_score' => 0,
            ]
        );
    }

    public function material()
    {
        $package = $this->currentPackage();
        return view('student.material', compact('package'));
    }

    public function practice()
    {
        $package = $this->currentPackage();
        $session = $this->currentSession();
        $questions = $package->questions()->orderBy('question_no')->get();
        $answers = PracticeAnswer::where('practice_session_id', $session->id)->get()->keyBy('question_no');

        return view('student.practice', compact('package', 'session', 'questions', 'answers'));
    }

    public function verifyAnswer(Request $request)
    {
        $data = $request->validate([
            'question_no' => ['required', 'integer', 'between:1,4'],
            'answer' => ['required', 'numeric'],
        ]);

        $package = $this->currentPackage();
        $session = $this->currentSession();
        $question = $package->questions()->where('question_no', $data['question_no'])->firstOrFail();

        $studentAnswer = round((float) $data['answer'], 3);
        $correctAnswer = round((float) $question->expected_current, 3);
        $isCorrect = abs($studentAnswer - $correctAnswer) <= 0.01;

        PracticeAnswer::updateOrCreate(
            [
                'practice_session_id' => $session->id,
                'question_no' => $question->question_no,
            ],
            [
                'voltage' => $question->voltage,
                'resistor_ohm' => $package->resistor_ohm,
                'student_answer' => $studentAnswer,
                'expected_answer' => $correctAnswer,
                'is_correct' => $isCorrect,
            ]
        );

        $quizScore = PracticeAnswer::where('practice_session_id', $session->id)->where('is_correct', true)->count();
        $session->update(['quiz_score' => $quizScore]);

        return response()->json([
            'ok' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $correctAnswer,
            'display' => sprintf('%s Volt : %s Ohm = %s %s %s', $question->voltage, $package->resistor_ohm, $studentAnswer, $isCorrect ? 'Benar' : 'Salah', $correctAnswer),
        ]);
    }

    public function startSensor(Request $request)
    {
        $data = $request->validate([
            'question_no' => ['required', 'integer', 'between:1,4'],
        ]);

        $package = $this->currentPackage();
        $session = $this->currentSession();
        $question = $package->questions()->where('question_no', $data['question_no'])->firstOrFail();

        $voltage = round($question->voltage + (random_int(-2, 2) / 20), 3);
        $resistance = round($package->resistor_ohm + random_int(-3, 3), 3);
        $current = round($voltage / max($resistance, 1), 3);
        $temperature = round(27 + random_int(0, 7) + ($current * 15), 2);

        $reading = SensorReading::create([
            'user_id' => Auth::id(),
            'practice_session_id' => $session->id,
            'source' => 'simulation',
            'voltage' => $voltage,
            'current' => $current,
            'resistance' => $resistance,
            'temperature' => $temperature,
        ]);

        PracticeAnswer::updateOrCreate(
            [
                'practice_session_id' => $session->id,
                'question_no' => $question->question_no,
            ],
            [
                'voltage' => $question->voltage,
                'resistor_ohm' => $package->resistor_ohm,
                'sensor_voltage' => $voltage,
                'sensor_current' => $current,
                'sensor_resistance' => $resistance,
                'sensor_temp' => $temperature,
            ]
        );

        return response()->json([
            'ok' => true,
            'reading' => $reading,
            'text' => sprintf('%s Volt : %s Ohm = %s Ampere | %s Celcius', $voltage, $resistance, $current, $temperature),
        ]);
    }

    public function processData(Request $request)
    {
        $data = $request->validate([
            'analysis_text' => ['nullable', 'string', 'max:5000'],
        ]);

        $package = $this->currentPackage();
        $session = $this->currentSession();
        $answers = PracticeAnswer::where('practice_session_id', $session->id)->orderBy('question_no')->get();

        $chart = [];
        foreach ($answers as $answer) {
            $chart[] = [
                'x' => (float) $answer->voltage,
                'y' => (float) $answer->sensor_resistance ?: (float) $answer->resistor_ohm,
            ];
        }

        $analysis = $data['analysis_text'] ?? 'Semakin besar tegangan, arus cenderung meningkat sesuai hukum Ohm. Nilai resistor pada paket tetap konstan sehingga grafik membentuk garis mendatar pada nilai hambatan yang sama.';

        $session->update([
            'analysis_text' => $analysis,
            'graph_json' => ['points' => $chart],
        ]);

        return response()->json([
            'ok' => true,
            'chart' => $chart,
            'analysis' => $analysis,
            'package' => $package->code,
        ]);
    }

    public function finish(Request $request)
    {
        $data = $request->validate([
            'analysis_text' => ['required', 'string', 'max:5000'],
        ]);

        $session = $this->currentSession();
        $quizScore = PracticeAnswer::where('practice_session_id', $session->id)->where('is_correct', true)->count();
        $practicalScore = PracticeAnswer::where('practice_session_id', $session->id)
            ->whereNotNull('sensor_voltage')
            ->count() > 0 ? 1 : 0;

        $total = min(100, ($quizScore * 20) + ($practicalScore * 10));

        $session->update([
            'attendance' => true,
            'quiz_score' => $quizScore,
            'practical_score' => $practicalScore,
            'manual_total_score' => $session->manual_total_score ?? 0,
            'total_score' => $total,
            'analysis_text' => $data['analysis_text'],
            'finished_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Semua data berhasil disimpan.',
            'total_score' => $total,
        ]);
    }
}
