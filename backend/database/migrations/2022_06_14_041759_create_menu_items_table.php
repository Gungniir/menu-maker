<?php

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
        Schema::create('menu_items', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('meal_id')->constrained('menu_meals');
            $table->foreignId('dish_id')->constrained();
            $table->smallInteger('day')->comment('0 - пн, 6 - вс');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
