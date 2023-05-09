<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->timestamp('game_date')->nullable();
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->string("winning_score")->nullable()->default(0);
            $table->string("losing_score")->nullable()->default(0);
            $table->bigInteger("winner_id")->nullable()->default(null);
            $table->bigInteger("loser_id")->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
