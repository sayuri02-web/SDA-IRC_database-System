<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->string('type'); // reminder, overdue, completed_late
            $table->text('message');
            $table->date('notification_date');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            // Unique constraint to prevent duplicate notifications per task per date per type
            $table->unique(['task_id', 'type', 'notification_date'], 'task_notif_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_notifications');
    }
};
