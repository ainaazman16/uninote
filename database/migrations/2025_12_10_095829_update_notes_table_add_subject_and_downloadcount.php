<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {

            // Add subject_id if not exists
            if (!Schema::hasColumn('notes', 'subject_id')) {
                $table->foreignId('subject_id')
                      ->after('provider_id')
                      ->constrained()
                      ->onDelete('cascade');
            }

            // Add download_count if not exists
            if (!Schema::hasColumn('notes', 'download_count')) {
                $table->unsignedInteger('download_count')
                      ->default(0)
                      ->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {

            if (Schema::hasColumn('notes', 'subject_id')) {
                $table->dropForeign(['subject_id']);
                $table->dropColumn('subject_id');
            }

            if (Schema::hasColumn('notes', 'download_count')) {
                $table->dropColumn('download_count');
            }

        });
    }
};
