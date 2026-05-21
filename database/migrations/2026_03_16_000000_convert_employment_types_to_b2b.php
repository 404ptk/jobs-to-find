<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('job_offers')
            ->whereIn('employment_type', ['contract', 'freelance'])
            ->update(['employment_type' => 'b2b']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We cannot distinguish which were 'contract' and which were 'freelance'
        // So we just leave them as 'b2b' or pick one if needed, but 'b2b' is the new standard.
    }
};
