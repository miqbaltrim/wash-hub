<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->string('plate_number', 20);
            $table->enum('vehicle_type', ['motor', 'mobil', 'suv', 'truck', 'bus']);
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->integer('year')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('plate_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};