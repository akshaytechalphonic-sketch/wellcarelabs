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
        Schema::table('inquiries', function (Blueprint $table) {
            // Add status column only if it doesn't already exist
            if (!Schema::hasColumn('inquiries', 'status')) {
                $table->enum('status', [
                    'Pending',
                    'Called',
                    'Interested',
                    'Not Interested',
                    'Booked',
                    'Lost'
                ])->default('Pending')->after('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('inquiries', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
