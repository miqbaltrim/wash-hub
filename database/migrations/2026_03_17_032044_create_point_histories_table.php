<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_head_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted']);
            $table->integer('points');
            $table->integer('balance_after')->default(0);
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->index(['customer_profile_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};