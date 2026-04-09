<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('delivery')->default(false);
            $table->decimal('member_discount', 8, 2)->default(0);
            $table->decimal('total', 8, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->string('order_status')->default('pending');
            $table->json('address')->nullable();
            $table->boolean('livraison')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
