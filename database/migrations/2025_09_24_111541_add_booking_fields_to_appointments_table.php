<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingFieldsToAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Add columns only if they do not exist already
            if (! Schema::hasColumn('appointments', 'package_id')) {
                $table->unsignedBigInteger('package_id')->nullable()->after('date');
                $table->index('package_id');
            }

            if (! Schema::hasColumn('appointments', 'package_name')) {
                $table->string('package_name')->nullable()->after('package_id');
            }

            if (! Schema::hasColumn('appointments', 'test_id')) {
                $table->unsignedBigInteger('test_id')->nullable()->after('package_name');
                $table->index('test_id');
            }

            if (! Schema::hasColumn('appointments', 'time_slot')) {
                // time type stores HH:MM:SS -- your UI sends HH:MM which is fine
                $table->time('time_slot')->nullable()->after('test_id');
            }

            if (! Schema::hasColumn('appointments', 'service')) {
                $table->string('service')->nullable()->after('time_slot');
            }

            if (! Schema::hasColumn('appointments', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('service');
            }

            if (! Schema::hasColumn('appointments', 'status')) {
                $table->string('status')->default('Pending')->after('total_price');
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // drop columns only if they exist
            if (Schema::hasColumn('appointments', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('appointments', 'total_price')) {
                $table->dropColumn('total_price');
            }
            if (Schema::hasColumn('appointments', 'service')) {
                $table->dropColumn('service');
            }
            if (Schema::hasColumn('appointments', 'time_slot')) {
                $table->dropColumn('time_slot');
            }
            if (Schema::hasColumn('appointments', 'test_id')) {
                $table->dropIndex(['test_id']);
                $table->dropColumn('test_id');
            }
            if (Schema::hasColumn('appointments', 'package_name')) {
                $table->dropColumn('package_name');
            }
            if (Schema::hasColumn('appointments', 'package_id')) {
                $table->dropIndex(['package_id']);
                $table->dropColumn('package_id');
            }
        });
    }
}
