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
        Schema::create('menu_scheme_repeat_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('menu_scheme_repeat_groups');
            $table->foreignId('meal_id')->constrained('menu_scheme_meals');
            $table->smallInteger('day')->comment('0 - пн, ..., 6 - вс');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_scheme_repeat_items');
    }
};
