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
        Schema::table('submission_responses', function (Blueprint $table) {
            // Make response_text nullable since video responses don't need text
            $table->text('response_text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission_responses', function (Blueprint $table) {
            // Revert back to not nullable (this might fail if there are null values)
            $table->text('response_text')->nullable(false)->change();
        });
    }
};