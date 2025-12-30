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
        Schema::table('payrolls', function (Blueprint $table) {
             // Use DB::statement for MariaDB compatibility
             DB::statement("ALTER TABLE payrolls CHANGE late_fee absent_fee DECIMAL(15, 2) DEFAULT 0");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
             DB::statement("ALTER TABLE payrolls CHANGE absent_fee late_fee DECIMAL(15, 2) DEFAULT 0");
        });
    }
};
