// database/migrations/2025_11_03_000000_create_coupons_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent','fixed']); // percent = % off, fixed = currency off
            $table->decimal('value', 10, 2);           // 10 => 10% if type=percent, or ₹10 if fixed
            $table->decimal('max_discount', 10, 2)->nullable(); // cap for % discounts
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->unsignedInteger('usage_limit')->nullable();     // total uses across all users
            $table->unsignedInteger('per_user_limit')->nullable();  // per-user cap
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('allowed_package_ids')->nullable(); // restrict to specific package IDs if needed
            $table->timestamps();
            $table->index(['is_active','expires_at','starts_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('coupons');
    }
};
