<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->unique();
            $table->unsignedBigInteger('member_id');
            $table->string('full_name');
            $table->string('partner_name')->nullable();
            $table->string('church_name')->nullable();
            $table->string('church_location')->nullable();
            $table->text('purpose')->nullable();
            $table->date('issued_date')->nullable();
            $table->string('chairman_name')->nullable();
            $table->string('issued_by')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_certificates');
    }
};
