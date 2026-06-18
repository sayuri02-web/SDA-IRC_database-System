<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add leader fields to members table
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('is_leader')->default(false)->after('photo');
            $table->string('organization')->nullable()->after('is_leader');
            $table->string('position')->nullable()->after('organization');
        });

        // Create organizations table
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('mdi-account-group');
            $table->string('color')->default('#2449d8');
            $table->string('bg_color')->default('#eef4ff');
            $table->timestamps();
        });

        // Seed default organizations
        DB::table('organizations')->insert([
            ['name' => 'Council Officers', 'icon' => 'mdi-shield-account', 'color' => '#2449d8', 'bg_color' => '#eef4ff', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AYC Officers', 'icon' => 'mdi-account-group', 'color' => '#28a745', 'bg_color' => '#e9fff3', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cluster 1 Officers', 'icon' => 'mdi-home-group', 'color' => '#2457ff', 'bg_color' => '#eef4ff', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cluster 2 Officers', 'icon' => 'mdi-home-group', 'color' => '#11a75c', 'bg_color' => '#e9fff3', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cluster 3 Officers', 'icon' => 'mdi-home-group', 'color' => '#ff8a00', 'bg_color' => '#fff4e8', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cluster 4 Officers', 'icon' => 'mdi-home-group', 'color' => '#8e3dff', 'bg_color' => '#f7ecff', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['is_leader', 'organization', 'position']);
        });
        Schema::dropIfExists('organizations');
    }
};
