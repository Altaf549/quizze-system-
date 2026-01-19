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
        Schema::table('results', function (Blueprint $table) {
            // Drop user_id column if it exists
            if (Schema::hasColumn('results', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            // Make device_id not nullable if it exists
            if (Schema::hasColumn('results', 'device_id')) {
                $table->string('device_id')->nullable(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // Restore user_id column
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Make device_id nullable again
            $table->string('device_id')->nullable()->change();
        });
    }
};
