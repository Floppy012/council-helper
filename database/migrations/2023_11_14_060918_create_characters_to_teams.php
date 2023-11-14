<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->index()->constrained('characters')->cascadeOnDelete();
            $table->foreignId('team_id')->index()->constrained('teams')->cascadeOnDelete();

            $table->unique(['character_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters_teams');
    }
};
