<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function latest()
    {
        return response()->json(
            SensorReading::orderByDesc('id')->first()
        );
    }

    public function ingest(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer'],
            'practice_session_id' => ['nullable', 'integer'],
            'source' => ['nullable', 'string', 'max:30'],
            'voltage' => ['required', 'numeric'],
            'current' => ['required', 'numeric'],
            'resistance' => ['required', 'numeric'],
            'temperature' => ['required', 'numeric'],
        ]);

        $reading = SensorReading::create([
            'user_id' => $data['user_id'] ?? null,
            'practice_session_id' => $data['practice_session_id'] ?? null,
            'source' => $data['source'] ?? 'esp32',
            'voltage' => $data['voltage'],
            'current' => $data['current'],
            'resistance' => $data['resistance'],
            'temperature' => $data['temperature'],
        ]);

        return response()->json(['ok' => true, 'data' => $reading]);
    }
}
