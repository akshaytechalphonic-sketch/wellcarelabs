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
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->string('test_code')->nullable()->after('slug');
            $table->string('sample_type')->nullable()->after('test_code');
            $table->string('fasting')->nullable()->after('sample_type');
            $table->integer('parameters_count')->nullable()->after('fasting');
            $table->text('why_done')->nullable()->after('description');
            $table->text('who_should_test')->nullable()->after('why_done');
            $table->text('how_to_read')->nullable()->after('who_should_test');
            $table->text('what_to_ask')->nullable()->after('how_to_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropColumn([
                'test_code',
                'sample_type',
                'fasting',
                'parameters_count',
                'why_done',
                'who_should_test',
                'how_to_read',
                'what_to_ask',
            ]);
        });
    }
};
