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
            // Add device_id column
            $table->string('device_id')->after('id');
            
            // Drop foreign key constraint for user_id
            $table->dropForeign(['user_id']);
            
            // Drop user_id column completely
            $table->dropColumn('user_id');
            
            // Add index for device_id for better performance
            $table->index('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
            
            // Restore user_id column
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }
};
