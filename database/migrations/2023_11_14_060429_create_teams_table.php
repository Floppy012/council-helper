<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            $table->string('name');
            $table->text('wowaudit_secret')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE teams ALTER public_id SET DEFAULT nanoid()');
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
