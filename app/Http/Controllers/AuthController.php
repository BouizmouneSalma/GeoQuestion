<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\http\Models\User;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=User::all();
        return view('index',$users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    { 
        $this->validate(
            $this->name = 'requierd|max:20'
        );

        $users=User::create();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
