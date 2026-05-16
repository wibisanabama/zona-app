<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('restrict');
            $table->unsignedInteger('quantity');
            $table->decimal('daily_rate', 12, 2);
            $table->decimal('deposit_amount', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->unsignedInteger('returned_quantity')->default(0);
            $table->enum('condition_on_return', ['baik', 'perlu_perbaikan', 'rusak'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};
