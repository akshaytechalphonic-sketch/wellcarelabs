<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHospitalsToAppointmentsFix extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('hospitals')) {
            Schema::create('hospitals', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('unique_id', 64)->unique();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('hospitals');
    }
}
