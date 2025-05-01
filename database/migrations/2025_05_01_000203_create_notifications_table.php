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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Can be null for broadcast notifications
            $table->string('type'); // 'product', 'order', 'review', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Store additional data like product_id, order_id, etc.
            $table->boolean('is_read')->default(false);
            $table->boolean('for_admin')->default(false); // False = customer notification, True = admin notification
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
