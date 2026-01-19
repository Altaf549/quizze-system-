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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('category_id');
            $table->integer('attempts_count')->default(0)->after('is_active');
            $table->decimal('rating', 3, 2)->default(0.00)->after('attempts_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'attempts_count', 'rating']);
        });
    }
};
