<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goodmoral_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->unique();
            $table->unsignedBigInteger('member_id');
            $table->string('full_name');
            $table->string('church_name')->nullable();
            $table->string('church_location')->nullable();
            $table->string('purpose')->nullable();
            $table->date('issued_date')->nullable();
            $table->string('elder_name')->nullable();
            $table->string('issued_by')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goodmoral_certificates');
    }
};
