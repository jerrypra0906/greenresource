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
        Schema::table('navigation_items', function (Blueprint $table) {
            // Drop the old string column
            $table->dropColumn('parent_id');
        });
        
        Schema::table('navigation_items', function (Blueprint $table) {
            // Add the correct foreign key column
            $table->unsignedBigInteger('parent_id')->nullable()->after('visible');
            $table->foreign('parent_id')->references('id')->on('navigation_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
        
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->string('parent_id')->nullable();
        });
    }
};
