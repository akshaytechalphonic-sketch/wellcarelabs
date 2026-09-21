<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportIdToAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // nullable, indexed, foreign key to reports.id
            $table->unsignedBigInteger('report_id')->nullable()->after('status')->index();

            // add FK safely if reports table exists
            if (Schema::hasTable('reports')) {
                $table->foreign('report_id')->references('id')->on('reports')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // drop FK first if exists
            if (Schema::hasTable('reports')) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->listTableDetails($table->getTable());
                if ($doctrineTable->hasForeignKey('appointments_report_id_foreign')) {
                    $table->dropForeign('appointments_report_id_foreign');
                }
            }
            $table->dropColumn('report_id');
        });
    }
}
