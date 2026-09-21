<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_package')) {
            Schema::create('page_package', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('page_id');
                $table->unsignedBigInteger('package_id');
                $table->timestamps();

                $table->foreign('page_id')->references('id')->on('dynamic_pages')->onDelete('cascade');
                $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('page_faq')) {
            Schema::create('page_faq', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('page_id');
                $table->unsignedBigInteger('faq_id');
                $table->timestamps();

                $table->foreign('page_id')->references('id')->on('dynamic_pages')->onDelete('cascade');
                $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_faq');
        Schema::dropIfExists('page_package');
    }
};
