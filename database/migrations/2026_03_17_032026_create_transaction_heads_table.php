<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_heads', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('customer_profile_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cashier_id')->constrained('users')->onDelete('restrict');
            $table->string('plate_number', 20)->nullable();
            $table->string('vehicle_type', 20)->nullable();
            $table->date('transaction_date');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2)->default(0);
            $table->enum('payment_method', ['cash', 'debit', 'credit_card', 'ewallet', 'transfer', 'free_reward']);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'refunded'])->default('pending');
            $table->decimal('payment_amount', 14, 2)->default(0);
            $table->decimal('change_amount', 14, 2)->default(0);
            $table->integer('points_earned')->default(0);
            $table->integer('points_redeemed')->default(0);
            $table->boolean('is_reward_claim')->default(false);
            $table->enum('wash_status', ['waiting', 'in_progress', 'done', 'picked_up'])->default('waiting');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['transaction_date', 'payment_status']);
            $table->index('invoice_number');
            $table->index('customer_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_heads');
    }
};