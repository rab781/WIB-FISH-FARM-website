<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category');  // Kategori pengeluaran
            $table->string('description'); // Deskripsi pengeluaran
            $table->decimal('amount', 12, 2); // Jumlah pengeluaran
            $table->date('expense_date'); // Tanggal pengeluaran
            $table->text('notes')->nullable(); // Catatan tambahan (opsional)
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
