<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(File::get(base_path('database/migrations/sql/2023_11_11_062513_nanoid.sql')));
    }
};
