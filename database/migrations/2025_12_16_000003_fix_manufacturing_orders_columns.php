<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Check if id_order column exists and id doesn't exist
        if (Schema::hasColumn('manufacturing_orders', 'id_order') && !Schema::hasColumn('manufacturing_orders', 'id')) {
            // Rename id_order to id
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->renameColumn('id_order', 'id');
            });
        }

        // If id column exists but has no sequence, set it as identity
        if (Schema::hasColumn('manufacturing_orders', 'id')) {
            // Check if id is already an identity column
            $isIdentity = DB::select("SELECT column_default FROM information_schema.columns WHERE table_name = 'manufacturing_orders' AND column_name = 'id'");
            
            if (empty($isIdentity[0]->column_default)) {
                // Drop the existing primary key constraint first
                DB::statement('ALTER TABLE manufacturing_orders DROP CONSTRAINT manufacturing_orders_pkey');
                
                // Rename the column if it's still id_order
                if (Schema::hasColumn('manufacturing_orders', 'id_order')) {
                    Schema::table('manufacturing_orders', function (Blueprint $table) {
                        $table->renameColumn('id_order', 'id');
                    });
                }
                
                // Add the primary key as identity
                DB::statement('ALTER TABLE manufacturing_orders ADD COLUMN id_temp BIGSERIAL');
                DB::statement('UPDATE manufacturing_orders SET id_temp = id');
                DB::statement('ALTER TABLE manufacturing_orders DROP COLUMN id');
                DB::statement('ALTER TABLE manufacturing_orders RENAME COLUMN id_temp TO id');
                DB::statement('ALTER TABLE manufacturing_orders ADD PRIMARY KEY (id)');
            }
        }
    }

    public function down(): void
    {
        // Reverse the changes if needed
        if (Schema::hasColumn('manufacturing_orders', 'id') && !Schema::hasColumn('manufacturing_orders', 'id_order')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->renameColumn('id', 'id_order');
            });
        }
    }
};

