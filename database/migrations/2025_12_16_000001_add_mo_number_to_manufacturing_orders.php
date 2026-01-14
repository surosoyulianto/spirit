<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add mo_number column if it doesn't exist
        if (!Schema::hasColumn('manufacturing_orders', 'mo_number')) {
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->string('mo_number')->nullable()->after('id');
            });

            // Generate mo_number for existing records
            DB::table('manufacturing_orders')
                ->whereNull('mo_number')
                ->update(['mo_number' => DB::raw("'MO-' || id::text")]);

            // Make it unique and not nullable
            Schema::table('manufacturing_orders', function (Blueprint $table) {
                $table->string('mo_number')->nullable(false)->change();
                $table->unique('mo_number');
            });
        }
    }

    public function down(): void
    {
        Schema::table('manufacturing_orders', function (Blueprint $table) {
            $table->dropUnique(['mo_number']);
            $table->dropColumn('mo_number');
        });
    }
};

