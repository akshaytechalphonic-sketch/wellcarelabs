<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabTestsTable extends Migration
{
    public function up()
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id(); // this replaces 'Sr. No' from excel
            $table->string('test_name');
            $table->decimal('mrp', 10, 2)->nullable();
            $table->decimal('b2b', 10, 2)->nullable();
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->string('banner_path')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lab_tests');
    }
}
