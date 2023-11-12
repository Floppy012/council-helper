<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            $table->text('url');
            $table->uuid('batch_id')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE reports ALTER public_id SET DEFAULT nanoid()');
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
