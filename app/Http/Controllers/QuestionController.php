<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Afficher la liste des questions
     */
    public function index(Request $request)
    {
        // Recherche par mots-clés ou lieu
        $query = Question::query()->with('user');
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%");
            });
        }
        
        // Tri par date (par défaut)
        $questions = $query->latest()->paginate(10);
        
        return view('questions.index', compact('questions'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Stocker une nouvelle question
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_name' => 'required|max:255',
        ]);
        
        $question = auth()->user()->questions()->create($validatedData);
        
        return redirect()->route('questions.show', $question)
            ->with('success', 'Question créée avec succès!');
    }

    /**
     * Afficher une question spécifique
     */
    public function show(Question $question)
    {
        $question->load(['answers.user', 'user']);
        
        return view('questions.show', compact('question'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Question $question)
    {
        $this->authorize('update', $question);
        
        return view('questions.edit', compact('question'));
    }

    /**
     * Mettre à jour une question
     */
    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);
        
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'location_name' => 'required|max:255',
        ]);
        
        $question->update($validatedData);
        
        return redirect()->route('questions.show', $question)
            ->with('success', 'Question mise à jour avec succès!');
    }

    /**
     * Supprimer une question
     */
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
        
        $question->delete();
        
        return redirect()->route('questions.index')
            ->with('success', 'Question supprimée avec succès!');
    }
}