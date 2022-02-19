<?php

use App\Models\Dish;
use App\Models\Tool;
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
        Schema::create('dish_tool', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dish::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tool::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dish_id', 'tool_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_tool');
    }
};
