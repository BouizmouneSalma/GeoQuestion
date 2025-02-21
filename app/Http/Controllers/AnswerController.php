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
    ]);
    
    $user_id = auth()->id();
    
    // Créer la réponse directement
    $answer = new Answer();
    $answer->content = $validatedData['content'];
    $answer->user_id = $user_id;
    $answer->question_id = $question->id;
    $answer->save();
    
    // Incrémenter le compteur de réponses
    $question->increment('answers_count');
    
    return redirect()->route('questions.show', $question)
        ->with('success', 'Réponse ajoutée avec succès!');
}

    /**
     * Mettre à jour une réponse
     */
    public function update(Request $request, Answer $answer)
    {
        // Vérification que l'utilisateur est le propriétaire
        $this->authorize('update', $answer);
        
        $validatedData = $request->validate([
            'content' => 'required',
        ]);
        
        $answer->update($validatedData);
        
        return redirect()->route('questions.show', $answer->question_id)
            ->with('success', 'Réponse mise à jour avec succès!');
    }

    
    public function destroy(Answer $answer)
    {
        // Vérification que l'utilisateur est le propriétaire
        $this->authorize('delete', $answer);
        
        $questionId = $answer->question_id;
        
        $answer->delete();
        
        // Décrémenter le compteur de réponses
        Question::find($questionId)->decrement('answers_count');
        
        return redirect()->route('questions.show', $questionId)
            ->with('success', 'Réponse supprimée avec succès!');
    }
}