<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function start()
    {
        $questions = Question::all();
        return view('exam.start', compact('questions'));
    }

    public function submit(Request $request)
    {
        $score = 0;
        $total = count($request->answers);

        foreach ($request->answers as $questionId => $answer) {
            $question = Question::find($questionId);
            if ($question->correct_option == $answer) {
                $score++;
            }
        }

        return view('exam.result', compact('score', 'total'));
    }
}
