<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\User;
use Tests\TestCase;

class DishTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index(): void
    {
        /**
         * @var User $user
         * @var User $secondUser
         */

        $user = User::factory()->has(
            Dish::factory()->count(10),
        )->createOne();

        $secondUser = User::factory()->has(
            Dish::factory()->count(10),
        )->create();

        $response = $this->actingAs($user)->get('/api/dish');
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertEquals(10, $json['total']);


        Dish::whereCreatorId($user->id)->forceDelete();
        Dish::whereCreatorId($secondUser->id)->forceDelete();

        $user->forceDelete();
        $secondUser->forceDelete();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->has(
            Dish::factory()->count(10),
        )->createOne();

        $response = $this->actingAs($user)->postJson('/api/dish', [
            'name' => 'Тестовое блюдо :)'
        ]);
        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Тестовое блюдо :)'
        ]);

//        $this->assertDatabaseHas(Dish::class, $response->json());

        Dish::whereCreatorId($user->id)->forceDelete();

        $user->forceDelete();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->has(
            Dish::factory()->count(10),
        )->createOne();

        $dish = $user->dishes->first();

        $response = $this->actingAs($user)->putJson("/api/dish/$dish->id", [
            'name' => 'Тестовое блюдо :)'
        ]);
        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Тестовое блюдо :)'
        ]);

//        $this->assertDatabaseHas(Dish::class, $response->json());

        Dish::whereCreatorId($user->id)->forceDelete();

        $user->forceDelete();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->has(
            Dish::factory()->count(10),
        )->createOne();

        $dish = $user->dishes->first();

        $response = $this->actingAs($user)->deleteJson("/api/dish/$dish->id");
        $response->assertStatus(200);

        $this->assertSoftDeleted(Dish::class, ['id' => $dish->id]);

        Dish::withTrashed()->whereCreatorId($user->id)->forceDelete();

        $user->forceDelete();
    }
}
