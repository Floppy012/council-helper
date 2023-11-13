<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raid_id')->constrained('raids')->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->tinyInteger('order');
            $table->unsignedSmallInteger('blizzard_dungeon_encounter_id')->nullable()->index();
            $table->unsignedSmallInteger('blizzard_journal_encounter_id')->nullable()->index();

            $table->unique(['raid_id', 'slug']);
            $table->unique(['raid_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};
