<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('manufacturing_orders', function (Blueprint $table) {
            $table->id();
            $table->string('mo_number')->unique();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->enum('status', ['draft', 'confirmed', 'in_progress', 'done', 'cancel'])->default('draft');
            $table->date('scheduled_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturing_orders');
    }
};
