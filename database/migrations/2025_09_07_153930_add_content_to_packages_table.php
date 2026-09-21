<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (! Schema::hasColumn('packages', 'content')) {
                $table->text('content')->nullable();
            }
            if (! Schema::hasColumn('packages', 'banner')) {
                $table->string('banner')->nullable();
            }
            if (! Schema::hasColumn('packages', 'status')) {
                $table->boolean('status')->default(true);
            }
            if (! Schema::hasColumn('packages', 'slug')) {
                $table->string('slug')->nullable()->unique();
            }
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'content')) {
                $table->dropColumn('content');
            }
            if (Schema::hasColumn('packages', 'banner')) {
                $table->dropColumn('banner');
            }
            if (Schema::hasColumn('packages', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('packages', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
