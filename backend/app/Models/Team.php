<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    public $timestamps = true;

    public function  players(): HasMany{

        return $this->hasMany(Player::class);
    }

    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class)
            ->using(TeamTournament::class)
            ->withPivot('active')
            ->withTimestamps();
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)
            ->using(GameTeam::class)
            ->withPivot('active')
            ->withTimestamps();
    }

}
