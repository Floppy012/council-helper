<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_sim_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analyzed_report_id')->constrained('analyzed_reports')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->cascadeOnDelete();
            $table->string('sim_slot');
            $table->string('profileset_name');
            $table->double('mean');
            $table->double('median');
            $table->double('min');
            $table->double('max');
            $table->double('mean_gain');
            $table->double('median_gain');
            $table->double('min_gain');
            $table->double('max_gain');

            $table->timestamps();
            $table->unique(['analyzed_report_id', 'item_id', 'encounter_id', 'sim_slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_sim_results');
    }
};
