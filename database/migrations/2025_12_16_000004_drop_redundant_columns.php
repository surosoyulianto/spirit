<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop the NOT NULL constraint on id_order column (if exists)
        if (Schema::hasColumn('manufacturing_orders', 'id_order')) {
            DB::statement('ALTER TABLE manufacturing_orders ALTER COLUMN id_order DROP NOT NULL');
        }

        // Drop the redundant id_order column
        if (Schema::hasColumn('manufacturing_orders', 'id_order')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->dropColumn('id_order');
            });
        }

        // Drop the redundant product column (keep product_id)
        if (Schema::hasColumn('manufacturing_orders', 'product')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->dropColumn('product');
            });
        }

        // Ensure proper data types
        if (Schema::hasColumn('manufacturing_orders', 'status')) {
            DB::statement("ALTER TABLE manufacturing_orders ALTER COLUMN status TYPE VARCHAR(20)");
            // Add enum check constraint if needed
        }
    }

    public function down(): void
    {
        // Cannot easily restore dropped columns
        // This migration is not easily reversible
    }
};

