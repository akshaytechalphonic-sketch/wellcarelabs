// database/migrations/2025_11_03_000001_create_coupon_usages_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete(); // link after checkout
            $table->unsignedInteger('quantity')->default(1); // times counted in this order
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('coupon_usages');
    }
};
