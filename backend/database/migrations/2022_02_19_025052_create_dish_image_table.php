<?php

use App\Models\Dish;
use App\Models\Image;
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
        Schema::create('dish_image', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dish::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Image::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dish_id', 'image_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_image');
    }
};
