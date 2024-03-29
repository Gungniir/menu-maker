<?php

use App\Enums\IngredientUnit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('ingredients', static function (Blueprint $table) {
            $table->enum('unit', IngredientUnit::values())->default(IngredientUnit::values()[0]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('ingredients', static function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};
