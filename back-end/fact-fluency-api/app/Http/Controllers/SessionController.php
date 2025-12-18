<?php

namespace App\Http\Controllers;

use App\Models\DrillSession;
use App\Models\DrillResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function start(Request $request)
{
    $validated = $request->validate([
        'operation_types' => 'required|array|min:1', // Changed to array
        'operation_types.*' => 'in:addition,subtraction,multiplication,division',
        'difficulty_level' => 'required|integer|min:1|max:5',
        'time_limit' => 'required|integer|min:30|max:300',
        'problems_count' => 'required|integer|min:5|max:50',
    ]);

    $session = DrillSession::create([
        'student_id' => $request->user()->id,
        'assignment_id' => null,
        'started_at' => now(),
        'total_problems' => $validated['problems_count'],
    ]);

    $problemController = new ProblemController();
    $problemsRequest = new Request([
        'operations' => $validated['operation_types'], // Now an array
        'difficulty' => $validated['difficulty_level'],
        'count' => $validated['problems_count'],
    ]);
    
    $problems = $problemController->generate($problemsRequest)->getData();

    return response()->json([
        'session_id' => $session->id,
        'problems' => $problems,
        'started_at' => $session->started_at,
    ], 201);
}

    public function submitAnswer(Request $request, DrillSession $session)
    {
        $validated = $request->validate([
            'problem' => 'required|array',
            'student_answer' => 'required|integer',
            'response_time_ms' => 'required|integer',
        ]);

        $isCorrect = $validated['student_answer'] == $validated['problem']['correct_answer'];

        DrillResponse::create([
            'session_id' => $session->id,
            'problem' => json_encode($validated['problem']),
            'correct_answer' => $validated['problem']['correct_answer'],
            'student_answer' => $validated['student_answer'],
            'is_correct' => $isCorrect,
            'response_time_ms' => $validated['response_time_ms'],
        ]);

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $validated['problem']['correct_answer'],
        ]);
    }

    public function complete(DrillSession $session)
    {
        $responses = DrillResponse::where('session_id', $session->id)->get();
        $correctCount = $responses->where('is_correct', true)->count();

        $session->update([
            'completed_at' => now(),
            'correct_answers' => $correctCount,
            'time_taken_seconds' => now()->diffInSeconds($session->started_at),
        ]);

        return response()->json([
            'total_problems' => $responses->count(),
            'correct_answers' => $correctCount,
            'accuracy' => $responses->count() > 0 ? round(($correctCount / $responses->count()) * 100) : 0,
            'time_taken' => $session->time_taken_seconds,
        ]);
    }

    public function index(Request $request)
    {
        $sessions = DrillSession::where('student_id', $request->user()->id)
            ->whereNotNull('completed_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sessions);
    }

    public function getMissedProblems(Request $request)
{
    $studentId = $request->user()->id;
    
    // Get all incorrect responses
    $missedProblems = DrillResponse::whereHas('session', function($query) use ($studentId) {
        $query->where('student_id', $studentId);
    })
    ->where('is_correct', false)
    ->select('problem', 'correct_answer', 'student_answer')
    ->get()
    ->map(function($response) {
        $problem = is_string($response->problem) ? json_decode($response->problem, true) : $response->problem;
        return [
            'num1' => $problem['num1'],
            'num2' => $problem['num2'],
            'operator' => $problem['operator'],
            'correct_answer' => $response->correct_answer,
            'student_answer' => $response->student_answer,
            'times_missed' => 1 // We'll enhance this later
        ];
    });

    // Group by problem to count frequency
    $problemGroups = $missedProblems->groupBy(function($item) {
        return $item['num1'] . '_' . $item['operator'] . '_' . $item['num2'];
    })->map(function($group) {
        $first = $group->first();
        $first['times_missed'] = $group->count();
        return $first;
    })->values()->sortByDesc('times_missed')->take(20);

    return response()->json($problemGroups);
}

public function startMissedProblemsSession(Request $request)
{
    $validated = $request->validate([
        'problems_count' => 'required|integer|min:5|max:50',
    ]);

    $studentId = $request->user()->id;
    
    // Get missed problems
    $missedProblems = DrillResponse::whereHas('session', function($query) use ($studentId) {
        $query->where('student_id', $studentId);
    })
    ->where('is_correct', false)
    ->inRandomOrder()
    ->limit($validated['problems_count'])
    ->get()
    ->map(function($response) {
        $problem = is_string($response->problem) ? json_decode($response->problem, true) : $response->problem;
        return [
            'num1' => $problem['num1'],
            'num2' => $problem['num2'],
            'operator' => $problem['operator'],
            'correct_answer' => $problem['correct_answer']
        ];
    });

    if ($missedProblems->isEmpty()) {
        return response()->json(['message' => 'No missed problems found!'], 404);
    }

    // Create session
    $session = DrillSession::create([
        'student_id' => $studentId,
        'assignment_id' => null,
        'started_at' => now(),
        'total_problems' => $missedProblems->count(),
    ]);

    return response()->json([
        'session_id' => $session->id,
        'problems' => $missedProblems,
        'started_at' => $session->started_at,
    ], 201);
}
}