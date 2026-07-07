<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_albums', function (Blueprint $table) {
            if (!Schema::hasColumn('gallery_albums', 'gradient_from')) {
                $table->string('gradient_from')->default('#667eea')->after('icon');
            }
            if (!Schema::hasColumn('gallery_albums', 'gradient_to')) {
                $table->string('gradient_to')->default('#764ba2')->after('gradient_from');
            }
            if (Schema::hasColumn('gallery_albums', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->dropColumn(['gradient_from', 'gradient_to']);
            $table->string('cover_image')->nullable()->after('description');
        });
    }
};
