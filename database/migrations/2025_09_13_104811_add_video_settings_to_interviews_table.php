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
        Schema::table('interviews', function (Blueprint $table) {
            // Video interview settings
            $table->boolean('allow_retakes')->default(true)->after('description');
            $table->integer('max_retakes_per_question')->default(3)->after('allow_retakes');
            $table->integer('max_video_duration')->default(300)->after('max_retakes_per_question'); // 5 minutes default
            $table->integer('preparation_time')->default(30)->after('max_video_duration'); // 30 seconds default
            $table->boolean('show_timer')->default(true)->after('preparation_time');
            $table->boolean('allow_pause')->default(false)->after('show_timer');
            
            // Interview flow settings
            $table->boolean('sequential_questions')->default(true)->after('allow_pause'); // Must answer in order
            $table->boolean('allow_skip')->default(false)->after('sequential_questions');
            $table->boolean('show_progress')->default(true)->after('allow_skip');
            
            // Technical settings
            $table->json('allowed_video_formats')->nullable()->after('show_progress'); // ['mp4', 'webm']
            $table->integer('max_video_size')->default(100)->after('allowed_video_formats'); // MB
            $table->string('video_quality')->default('720p')->after('max_video_size'); // 480p, 720p, 1080p
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn([
                'allow_retakes',
                'max_retakes_per_question',
                'max_video_duration',
                'preparation_time',
                'show_timer',
                'allow_pause',
                'sequential_questions',
                'allow_skip',
                'show_progress',
                'allowed_video_formats',
                'max_video_size',
                'video_quality'
            ]);
        });
    }
};