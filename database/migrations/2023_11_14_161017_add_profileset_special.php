<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_sim_results', function (Blueprint $table) {
            $table->string('profileset_special')->nullable();
            $table->dropUnique(['analyzed_report_id', 'item_id', 'encounter_id', 'sim_slot']);
            $table->unique(['analyzed_report_id', 'item_id', 'encounter_id', 'sim_slot', 'profileset_special']);
        });
    }
};
