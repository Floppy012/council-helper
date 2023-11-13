<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->cascadeOnDelete();
            $table->unsignedInteger('blizzard_item_id');
            $table->string('name');
            $table->string('icon_slug');
            $table->boolean('catalyst')->default(false);
            $table->foreignId('catalyst_source_item_id')->nullable()->constrained('items')->cascadeOnDelete();

            $table->unique(['encounter_id', 'blizzard_item_id']);
        });

        DB::statement('ALTER TABLE items ALTER public_id SET DEFAULT nanoid()');
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
