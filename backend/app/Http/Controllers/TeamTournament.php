<?php

namespace App\Http\Controllers;

use {{ namespacedModel }};
use App\Models\m;
use Illuminate\Http\Request;

class TeamTournament extends Controller
{
    /**
     * Show the form for creating the new resource.
     */
    public function create(m $m): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request, m $m): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function show(m $m)
    {
        //
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(m $m)
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request, m $m)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(m $m): never
    {
        abort(404);
    }
}
