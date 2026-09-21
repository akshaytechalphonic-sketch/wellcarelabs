<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'mrp')) {
                $table->decimal('mrp', 10, 2)->nullable()->after('description');
            }
            if (!Schema::hasColumn('services', 'b2b')) {
                $table->decimal('b2b', 10, 2)->nullable()->after('mrp');
            }
            if (!Schema::hasColumn('services', 'discounted_price')) {
                $table->decimal('discounted_price', 10, 2)->nullable()->after('b2b');
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['mrp','b2b','discounted_price']);
        });
    }
};
