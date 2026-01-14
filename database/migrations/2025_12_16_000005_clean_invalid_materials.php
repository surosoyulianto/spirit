<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Delete manufacturing_order_materials records with invalid product_id
        DB::statement("
            DELETE FROM manufacturing_order_materials
            WHERE product_id NOT IN (SELECT id FROM products)
        ");
    }

    public function down(): void
    {
        // Cannot restore deleted records
    }
};

