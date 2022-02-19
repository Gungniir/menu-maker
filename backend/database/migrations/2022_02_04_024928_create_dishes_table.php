<?php

use App\Models\User;
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
        Schema::create('dishes', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->comment('Создатель блюда')->constrained('users');
            $table->string('name')->comment('Название блюда');
            $table->boolean('is_archive')->default(false)->comment('Находится ли блюдо в архиве');
            $table->unsignedSmallInteger('cooking_time')->nullable()->comment('Время приготовления в минутах');
            $table->unsignedSmallInteger('kcal')->nullable()->comment('Килокалории в расчёте на одну порцию');
            $table->unsignedSmallInteger('proteins')->nullable()->comment('Белки в расчёте на одну порцию');
            $table->unsignedSmallInteger('fats')->nullable()->comment('Жиры в расчёте на одну порцию');
            $table->unsignedSmallInteger('carbohydrates')->nullable()->comment('Углеводы в расчёте на одну порцию');
            $table->string('link', 1000)->nullable()->comment('Ссылка на источник блюда');
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
        Schema::dropIfExists('dishes');
    }
};
