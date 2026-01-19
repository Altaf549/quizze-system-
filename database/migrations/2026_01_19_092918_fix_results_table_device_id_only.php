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
            // Add device_id column if it doesn't exist
            if (!Schema::hasColumn('results', 'device_id')) {
                $table->string('device_id')->after('id');
                $table->index('device_id');
            }
            
            // Remove user_id column and foreign key if they exist
            if (Schema::hasColumn('results', 'user_id')) {
                // Check if foreign key exists before dropping
                $foreignKeys = \DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'results' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
                
                if (!empty($foreignKeys)) {
                    $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
                    \DB::statement("ALTER TABLE results DROP FOREIGN KEY $constraintName");
                }
                
                $table->dropColumn('user_id');
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
            
            // Remove device_id column
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });
    }
};
