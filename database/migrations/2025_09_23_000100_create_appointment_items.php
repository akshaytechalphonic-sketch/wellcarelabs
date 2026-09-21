<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // appointment_items table
        Schema::create('appointment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->string('item_type', 50); // e.g. 'package', 'labtest', 'addon'
            $table->unsignedBigInteger('item_id')->nullable(); // original product id (optional)
            $table->string('item_name'); // copy at booking time
            $table->decimal('item_price', 10, 2); // price captured at booking time
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        // optional: add total_price summary to appointments (nullable for backwards compat)
        Schema::table('appointments', function (Blueprint $table) {
            if (! Schema::hasColumn('appointments', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });

        Schema::dropIfExists('appointment_items');
    }
};
