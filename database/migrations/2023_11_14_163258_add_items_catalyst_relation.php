<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('catalyst_items_2_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('catalyst_item_id')->constrained('items')->cascadeOnDelete();

            $table->unique(['item_id', 'catalyst_item_id']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('catalyst_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalyst_items_2_items');
    }
};
