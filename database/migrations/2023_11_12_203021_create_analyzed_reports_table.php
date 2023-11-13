<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analyzed_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('character_id')->nullable()->constrained('characters')->nullOnDelete();
            $table->foreignId('raid_id')->constrained('raids')->cascadeOnDelete();
            $table->string('raid_difficulty');
            $table->unsignedTinyInteger('class_id');
            $table->unsignedTinyInteger('race_id');
            $table->unsignedTinyInteger('spec_id');
            $table->double('dps_mean');
            $table->double('dps_median');
            $table->double('dps_min');
            $table->double('dps_max');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analyzed_reports');
    }
};
