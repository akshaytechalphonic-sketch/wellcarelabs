<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('is_special')->default(false)->after('status');
            $table->string('special_label')->nullable()->after('is_special'); // e.g. "Special", "Limited Offer"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['is_special', 'special_label']);
        });
    }
};
