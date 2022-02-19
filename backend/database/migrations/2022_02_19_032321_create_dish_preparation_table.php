<?php

use App\Models\Dish;
use App\Models\Preparation;
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
        Schema::create('dish_preparation', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dish::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Preparation::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dish_id', 'preparation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_preparation');
    }
};
