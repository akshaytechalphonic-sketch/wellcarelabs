<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // MySQL enum modification: list ALL existing enum values + the new one.
        // Update the list below to match your current enum values exactly.
        DB::statement("ALTER TABLE `appointments` MODIFY `status` ENUM('Pending','Approved','Reschedule','Completed','Cancelled') NOT NULL DEFAULT 'Pending'");
    }

    public function down()
    {
        // revert to previous enum (remove 'Reschedule')
        DB::statement("ALTER TABLE `appointments` MODIFY `status` ENUM('Pending','Approved','Completed','Cancelled') NOT NULL DEFAULT 'Pending'");
    }
};
