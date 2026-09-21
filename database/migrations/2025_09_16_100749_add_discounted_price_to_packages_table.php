<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Add discounted_price if it doesn't exist (safe guard)
            if (! Schema::hasColumn('packages', 'discounted_price')) {
                $table->decimal('discounted_price', 10, 2)->nullable()->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'discounted_price')) {
                $table->dropColumn('discounted_price');
            }
        });
    }
};
