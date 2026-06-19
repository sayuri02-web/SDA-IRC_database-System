<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dedication_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->unique();
            $table->unsignedBigInteger('member_id');
            $table->string('child_name');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('dedication_date')->nullable();
            $table->string('church_name')->nullable();
            $table->string('church_location')->nullable();
            $table->string('chairman_name')->nullable();
            $table->string('minister_name')->nullable();
            $table->text('witnesses')->nullable();
            $table->string('issued_by')->default('Admin');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dedication_certificates');
    }
};
