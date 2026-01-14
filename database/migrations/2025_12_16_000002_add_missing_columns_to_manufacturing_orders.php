<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add product_id column if it doesn't exist
        if (!Schema::hasColumn('manufacturing_orders', 'product_id')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->foreignId('product_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        // Add quantity column if it doesn't exist
        if (!Schema::hasColumn('manufacturing_orders', 'quantity')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->integer('quantity')->default(1)->after('product_id');
            });
        }

        // Add scheduled_date column if it doesn't exist
        if (!Schema::hasColumn('manufacturing_orders', 'scheduled_date')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->date('scheduled_date')->nullable()->after('quantity');
            });
        }

        // Add notes column if it doesn't exist
        if (!Schema::hasColumn('manufacturing_orders', 'notes')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('scheduled_date');
            });
        }

        // Add status column if it doesn't exist
        if (!Schema::hasColumn('manufacturing_orders', 'status')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->enum('status', ['draft', 'confirmed', 'in_progress', 'done', 'cancel'])->default('draft')->after('notes');
            });
        }

        // Add completed_date column if it doesn't exist
        if (!Schema::hasColumn('manufacturing_orders', 'completed_date')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->date('completed_date')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('manufacturing_orders', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('manufacturing_orders', function (Blueprint $table) {
            $table->dropColumn(['product_id', 'quantity', 'scheduled_date', 'notes', 'status', 'completed_date']);
        });
    }
};

