<?php

use App\Models\Dish;
use App\Models\Ingredient;
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
        Schema::create('dish_ingredient', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dish::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Ingredient::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount')->comment('Количество ингредиента в расчёте на одну порцию');
            $table->timestamps();

            $table->unique(['dish_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_ingredient');
    }
};
