<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim')->unique();
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('study_program_id')->constrained('study_programs')->onDelete('cascade')->onUpdate('cascade');
            $table->string('slug')->unique();
            $table->string('secret_code')->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('progress')->default(0);
            $table->string('certificate_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
