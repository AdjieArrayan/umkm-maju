<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('unit'); // Kg, Pcs, Dus, dll

            $table->integer('stock')->default(0); // stok AKTUAL
            $table->integer('minimum_stock')->default(0);

            $table->decimal('price', 12, 2)->default(0);
            $table->string('image')->nullable(); // path gambar

            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
