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
        if (! Schema::hasColumn('lab_tests', 'description')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                // place after the correct column name: banner_path
                $table->text('description')->nullable()->after('banner_path');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('lab_tests', 'description')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
