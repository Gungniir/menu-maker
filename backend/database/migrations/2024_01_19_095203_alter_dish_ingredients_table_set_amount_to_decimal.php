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
        Schema::table('dish_ingredient', function (Blueprint $table) {
            $table->unsignedDecimal('amount', 16, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dish_ingredient', function (Blueprint $table) {
            $table->integer('amount')->change();
        });
    }
};
