<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Stocker une nouvelle réponse
     */
    public function store(Request $request, Question $question = null)
    {
        $validatedData = $request->validate([
            'content' => 'required',
            'question_id' => 'required_without:question|exists:questions,id',
        ]);
        
        // Si question est fournie par la route
        if ($question) {
            $validatedData['question_id'] = $question->id;
        }
        
        $answer = auth()->user()->answers()->create($validatedData);
        
        // Incrémenter le compteur de réponses
        $question = Question::find($validatedData['question_id']);
        $question->increment('answers_count');
        
        return redirect()->route('questions.show', $question)
            ->with('success', 'Réponse ajoutée avec succès!');
    }
}