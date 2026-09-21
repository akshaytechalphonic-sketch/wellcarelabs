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
        // 1. Drop package_id column from lab_tests
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropColumn('package_id');
        });

        // 2. Create the package_lab_test pivot table
        Schema::create('package_lab_test', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('lab_test_id');

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('lab_test_id')->references('id')->on('lab_tests')->onDelete('cascade');

            $table->primary(['package_id', 'lab_test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_lab_test');

        Schema::table('lab_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable()->after('page_id');
        });
    }
};
