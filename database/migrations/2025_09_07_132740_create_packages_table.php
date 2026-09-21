<?php

// database/migrations/2025_xx_xx_create_packages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('banner')->nullable(); // path in storage
            $table->boolean('status')->default(true); // active or not
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('packages');
    }
};
