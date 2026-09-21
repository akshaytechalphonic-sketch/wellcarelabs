<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBannerPathFromLabTestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Only attempt to drop if the column actually exists
        if (Schema::hasColumn('lab_tests', 'banner_path')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->dropColumn('banner_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Recreate the column if it does not exist (safe rollback)
        if (! Schema::hasColumn('lab_tests', 'banner_path')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                // recreate as nullable string; adjust length if you used different type before
                $table->string('banner_path')->nullable();
            });
        }
    }
}
