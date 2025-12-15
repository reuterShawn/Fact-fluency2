<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProblemController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'operation' => 'required|in:addition,subtraction,multiplication,division',
            'difficulty' => 'required|integer|min:1|max:5',
            'count' => 'required|integer|min:1|max:50',
        ]);

        $problems = [];
        
        for ($i = 0; $i < $validated['count']; $i++) {
            $problem = $this->generateSingleProblem(
                $validated['operation'], 
                $validated['difficulty']
            );
            $problems[] = $problem;
        }

        return response()->json($problems);
    }

    private function generateSingleProblem($operation, $difficulty)
    {
        $maxNumber = $difficulty * 10;

        switch ($operation) {
            case 'addition':
                $num1 = rand(0, $maxNumber);
                $num2 = rand(0, $maxNumber);
                return [
                    'num1' => $num1,
                    'num2' => $num2,
                    'operator' => '+',
                    'correct_answer' => $num1 + $num2
                ];

            case 'subtraction':
                $num2 = rand(0, $maxNumber);
                $num1 = $num2 + rand(0, $maxNumber);
                return [
                    'num1' => $num1,
                    'num2' => $num2,
                    'operator' => '-',
                    'correct_answer' => $num1 - $num2
                ];

            case 'multiplication':
                $num1 = rand(0, min(12, $difficulty * 3));
                $num2 = rand(0, min(12, $difficulty * 3));
                return [
                    'num1' => $num1,
                    'num2' => $num2,
                    'operator' => '×',
                    'correct_answer' => $num1 * $num2
                ];

            case 'division':
                $num2 = rand(1, min(12, $difficulty * 3));
                $result = rand(0, $maxNumber);
                $num1 = $num2 * $result;
                return [
                    'num1' => $num1,
                    'num2' => $num2,
                    'operator' => '÷',
                    'correct_answer' => $result
                ];

            default:
                return null;
        }
    }
}