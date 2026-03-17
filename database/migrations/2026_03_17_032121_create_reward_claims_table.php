<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_head_id')->nullable()->constrained()->onDelete('set null');
            $table->string('reward_type', 50)->default('free_wash');
            $table->integer('washes_required')->default(10);
            $table->integer('washes_at_claim');
            $table->enum('status', ['claimed', 'used', 'expired', 'cancelled'])->default('claimed');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_profile_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_claims');
    }
};