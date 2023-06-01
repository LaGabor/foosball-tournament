<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory;


    protected $fillable = [
        'game_date',
        'winner_id',
        'looser_id',
        'winning_score',
        'losing_score'
    ];

    public $timestamps = true;

    public function  tournaments(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }


    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    public function loser()
    {
        return $this->belongsTo(Team::class, 'loser_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->using(GameTeam::class)
            ->withPivot('active')
            ->withTimestamps();
    }

}
