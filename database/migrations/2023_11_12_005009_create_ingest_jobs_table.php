<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingest_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('type')->index();
            $table->string('status')->index();
            $table->json('errors')->nullable();
            $table->uuid('job_id')->nullable();
            $table->timestamps();

            $table->unique(['report_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingest_jobs');
    }
};
