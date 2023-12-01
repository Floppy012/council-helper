<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analyzed_reports', function (Blueprint $table) {
            $table->foreignId('superseding_id')
                ->nullable()
                ->after('id')
                ->constrained('analyzed_reports')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('analyzed_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('superseding_id');
            $table->dropColumn('superseding_id');
        });
    }
};
