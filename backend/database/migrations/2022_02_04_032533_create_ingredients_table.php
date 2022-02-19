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
        Schema::create('ingredients', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->string('name')->comment('Название продукта');
            $table->boolean('is_perishable')->comment('Скоропортящийся ли продукт');
            $table->unsignedInteger('amount')->comment('Количество у создателя');
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
        Schema::dropIfExists('ingredients');
    }
};
