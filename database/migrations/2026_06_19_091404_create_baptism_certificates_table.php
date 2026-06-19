<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baptism_certificates', function (Blueprint $table) {
            $table->id();

            $table->string('certificate_no')->unique();

            $table->foreignId('member_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // MEMBER SNAPSHOT (important kay printed certificate ni)
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();

            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();

            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();

            // BAPTISM INFO (from blade)
            $table->string('baptism_place')->nullable();
            $table->string('officiating_minister')->nullable();
            $table->string('chairman')->nullable();
            $table->string('secretary')->nullable();

            $table->date('fellowship_date')->nullable();

            // split date fields
            $table->integer('baptism_day')->nullable();
            $table->string('baptism_month')->nullable();
            $table->integer('baptism_year')->nullable();

            $table->string('church_fellowship')->nullable();

            // DOCUMENT INFO
            $table->string('doc_no')->nullable();
            $table->string('page_no')->nullable();
            $table->string('book_no')->nullable();
            $table->string('series_no')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baptism_certificates');
    }
};