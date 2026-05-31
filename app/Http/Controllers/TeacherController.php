<?php

namespace App\Http\Controllers;

use App\Models\PracticeSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class TeacherController extends Controller
{
    public function __construct()
    {
        if (auth()->check() && auth()->user()->role !== 'guru') {
            abort(403);
        }
    }

    public function dashboard()
    {
        $totalStudents = User::where('role', 'siswa')->count();
        $finished = PracticeSession::whereNotNull('finished_at')->count();
        $avgScore = round((float) PracticeSession::whereNotNull('finished_at')->avg('total_score'), 2);

        return view('teacher.dashboard', compact('totalStudents', 'finished', 'avgScore'));
    }

    public function practiceIndex(Request $request)
    {
        $search = $request->get('search');

        $sessions = PracticeSession::with(['user', 'package'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('updated_at')
            ->get();

        return view('teacher.practice', compact('sessions', 'search'));
    }

    public function savePractice(Request $request)
    {
        $payload = $request->validate([
            'records' => ['required', 'array'],
            'records.*.session_id' => ['required', 'integer'],
            'records.*.attendance' => ['nullable', 'boolean'],
            'records.*.total_score' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        foreach ($payload['records'] as $row) {
            $session = PracticeSession::find($row['session_id']);
            if (! $session) continue;

            $session->attendance = (bool) ($row['attendance'] ?? false);
            $session->manual_total_score = (int) ($row['total_score'] ?? 0);
            $session->total_score = (int) ($row['total_score'] ?? $session->total_score ?? 0);
            $session->save();
        }

        return back()->with('success', 'Data praktikum berhasil disimpan.');
    }

    public function users(Request $request)
    {
        $search = $request->get('search');

        $users = User::when($search, function ($query) use ($search) {
                $query->where('nis', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
            })
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('teacher.users', compact('users', 'search'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'nis' => ['nullable', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'in:guru,siswa'],
        ]);

        User::create([
            'nis' => $data['nis'] ?? null,
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'nis' => ['nullable', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'string', 'min:4'],
            'role' => ['required', 'in:guru,siswa'],
        ]);

        $user->nis = $data['nis'] ?? null;
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->role = $data['role'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['delete' => 'Tidak bisa menghapus akun sendiri.']);
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
