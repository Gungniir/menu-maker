<?php

use App\Models\Dish;
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
        Schema::create('recipe_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dish::class)->comment('Блюдо')->constrained();
            $table->string('item')->comment('Сам элемент рецепта');
            $table->smallInteger('order')->comment('Порядок элементов рецепта');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_items');
    }
};
