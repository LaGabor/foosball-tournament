<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        '/players/new',
        '/teams/new',
        '/players/*/edit',
        '/teams/*/edit',
        '/tournaments/new',
        '/tournaments/start',
        '/tournaments/*/edit',
        '/tournament/game/play',
    ];
}
