<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('test_name');
        });

        // Backfill slugs for existing records
        $tests = DB::table('lab_tests')->get();
        foreach ($tests as $t) {
            $slug = Str::slug($t->test_name);
            $originalSlug = $slug;
            $count = 1;
            // Check uniqueness within the database
            while (DB::table('lab_tests')->where('slug', $slug)->where('id', '!=', $t->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            DB::table('lab_tests')->where('id', $t->id)->update(['slug' => $slug]);
        }

        Schema::table('lab_tests', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
