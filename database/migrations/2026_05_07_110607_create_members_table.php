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
        Schema::create('members', function (Blueprint $table) {
    
            $table->id();
    
            // PERSONAL INFO
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
    
            $table->string('gender');
    
            $table->date('birthdate');
            $table->string('birthplace')->nullable();
    
            $table->string('mobile')->nullable();
    
            // PARENTS INFO
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
    
            // ADDRESS
            $table->string('region');
            $table->string('province');
            $table->string('city');
            $table->string('barangay');
            $table->string('street')->nullable();
    
            // OTHER INFO
            $table->integer('cluster')->nullable();
    
            $table->date('baptism_date')->nullable();
            $table->string('baptism_place')->nullable();

            $table->string('membership_status')->default('baptized');
    
            $table->string('officiating_minister')->nullable();
    
            // PHOTO
            $table->longText('photo')->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};