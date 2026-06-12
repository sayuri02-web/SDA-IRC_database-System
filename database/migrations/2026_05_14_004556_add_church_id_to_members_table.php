<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {

            $table->unsignedBigInteger('church_id')->nullable();

            $table->foreign('church_id')
                ->references('id')
                ->on('churches')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {

            $table->dropForeign(['church_id']);

            $table->dropColumn('church_id');
        });
    }
};
