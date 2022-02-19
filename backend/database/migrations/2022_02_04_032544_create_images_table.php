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
        Schema::create('images', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->string('name')->comment('Название файла');
            $table->string('filename', 128)->comment('Имя файла в фс в папке пользователя');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['creator_id', 'filename']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
