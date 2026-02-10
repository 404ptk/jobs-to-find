<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('company_name');
            $table->string('salary_range')->nullable();
            $table->string('employment_type')->default('full-time'); // full-time, part-time, contract, internship
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->date('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('title');
            $table->index('is_active');
            $table->index('category_id');
            $table->index('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
