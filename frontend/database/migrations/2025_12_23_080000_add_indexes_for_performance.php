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
        Schema::table('pages', function (Blueprint $table) {
            // Add index on status for faster filtering
            $table->index('status');
            // Add index on slug and status together for common queries
            $table->index(['slug', 'status']);
        });

        Schema::table('sections', function (Blueprint $table) {
            // Add index on page_id and order for faster section retrieval
            $table->index(['page_id', 'order']);
            // Add index on type for filtering sections by type
            $table->index('type');
        });

        Schema::table('media', function (Blueprint $table) {
            // Add index on file_path for faster lookups
            $table->index('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['slug', 'status']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropIndex(['page_id', 'order']);
            $table->dropIndex(['type']);
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['file_path']);
        });
    }
};

