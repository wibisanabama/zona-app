<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->foreignId('cashier_id')->constrained('users')->onDelete('restrict');
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['tunai', 'transfer', 'qris', 'edc'])->default('tunai');
            $table->enum('type', ['dp', 'pelunasan', 'denda', 'refund_deposit'])->default('pelunasan');
            $table->dateTime('paid_at')->useCurrent();
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
