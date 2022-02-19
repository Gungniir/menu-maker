<?php

use App\Models\Dish;
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
        Schema::create('preparations', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->foreignIdFor(Dish::class)->nullable()->comment('Если подготовка - готовка блюда')->constrained();
            $table->string('name')->nullable()->comment('Если подготовка - не готовка блюда');
            $table->unsignedSmallInteger('preparation_time')->nullable()->comment('Если подготовка - не готовка блюда (в минутах)');
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
        Schema::dropIfExists('preparations');
    }
};
