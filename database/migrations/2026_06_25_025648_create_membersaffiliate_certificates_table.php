<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membersaffiliate_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->unique();
            $table->unsignedBigInteger('member_id');
            $table->string('full_name');
            $table->string('church_name')->nullable();
            $table->string('church_location')->nullable();
            $table->string('residence_cert_no')->nullable();
            $table->string('residence_issued_at')->nullable();
            $table->date('residence_issued_date')->nullable();
            $table->date('done_date')->nullable();
            $table->string('elder_name')->nullable();
            $table->string('secretary_name')->nullable();
            $table->string('chairman_name')->nullable();
            $table->string('issued_by')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membersaffiliate_certificates');
    }
};
