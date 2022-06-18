<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpParser\ErrorHandler\Collecting;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Проверка индексации пользователей для обычного пользователя
     *
     * Предполагается, что пользователь не имеет права на индексацию
     *
     * @return void
     */
    public function test_index(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->createOne(['id' => 2]);

        $response = $this->actingAs($user)->get('/api/user');
        $response->assertStatus(403);

        $user->forceDelete();
    }

    /**
     * Проверка индексации пользователей для администратора
     *
     * Предполагается, что администратор получает пагинацию всех пользователей
     *
     * @return void
     */
    public function test_index_admin(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->makeOne(['id' => 1]);
        /**
         * @var User[]|Collection $user
         */
        $users = User::factory()->count(20)->create();

        $response = $this->actingAs($user)->get('/api/user');
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertEquals(20, $json['total']);

        User::destroy($users->map(fn($item) => $item->id));
    }

    /**
     * Проверка создания пользователя для гостя
     *
     * Предполагается, что эта операция доступна для всех
     *
     * @return void
     */
    public function test_store(): void
    {
        $response = $this->postJson('/api/user', [
            'name' => 'Пётр Иванович',
            'email' => 'test@gmail.com',
            'password' => '123456'
        ]);
        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Пётр Иванович',
            'email' => 'test@gmail.com',
        ]);

        $id = $response->json('id');

//        $this->assertDatabaseHas(User::class, $response->json());

        User::destroy($id);
    }

    /**
     * Проверка редактирования пользователя (самого себя)
     *
     * Предполагается, что эта операция доступна для всех
     *
     * @return void
     */
    public function test_update(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->createOne();

        $response = $this->actingAs($user)->putJson("/api/user/$user->id", [
            'name' => 'Тестовое блюдо :)',
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Тестовое блюдо :)'
        ]);

//        $this->assertDatabaseHas(User::class, $response->json());

        $user->forceDelete();
    }

    /**
     * Проверка редактирования пользователя (самого себя)
     *
     * Предполагается, что эта операция доступна только админам
     *
     * @return void
     */
    public function test_update_another(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->createOne();
        /**
         * @var User $user2
         */
        $user2 = User::factory()->createOne();

        $response = $this->actingAs($user2)->putJson("/api/user/$user->id", [
            'name' => 'Тестовое блюдо :)',
            'email' => 'test@gmail.com',
            'password' => null,
            'telegram_id' => $user->telegram_id
        ]);
        $response->assertStatus(403);

        $user->forceDelete();
        $user2->forceDelete();
    }

    /**
     * Проверка редактирования пользователя (самого себя)
     *
     * Предполагается, что эта операция доступна только админам
     *
     * @return void
     */
    public function test_update_admin(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->makeOne(['id' => 1]);
        /**
         * @var User $user2
         */
        $user2 = User::factory()->createOne();

        $response = $this->actingAs($user)->putJson("/api/user/$user2->id", [
            'name' => 'Тестовое блюдо :)',
            'email' => 'test@gmail.com',
            'password' => null,
            'telegram_id' => $user->telegram_id
        ]);
        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Тестовое блюдо :)',
            'email' => 'test@gmail.com',
            'telegram_id' => $user->telegram_id
        ]);

//        $this->assertDatabaseHas(User::class, $response->json());

        $user2->forceDelete();
    }

    /**
     * Проверка удаления пользователя (самого себя)
     *
     * Предполагается, что эта операция доступна всем
     *
     * @return void
     */
    public function test_delete(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->createOne();

        $response = $this->actingAs($user)->deleteJson("/api/user/$user->id");
        $response->assertStatus(200);

        $this->assertSoftDeleted(User::class, ['id' => $user->id]);

        $user->forceDelete();
    }

    /**
     * Проверка удаления пользователя (другого)
     *
     * Предполагается, что эта операция доступна только админам
     *
     * @return void
     */
    public function test_delete_another(): void
    {
        /**
         * @var User $user
         * @var User $user2
         */
        $user = User::factory()->createOne();
        $user2 = User::factory()->createOne();

        $response = $this->actingAs($user2)->deleteJson("/api/user/$user->id");
        $response->assertStatus(403);

        $user->forceDelete();
        $user2->forceDelete();
    }

    /**
     * Проверка удаления пользователя (админ)
     *
     * Предполагается, что эта операция доступна только админам
     *
     * @return void
     */
    public function test_delete_admin(): void
    {
        /**
         * @var User $user
         * @var User $user2
         */
        $user = User::factory()->createOne();
        $user2 = User::factory()->makeOne(['id' => 1]);

        $response = $this->actingAs($user2)->deleteJson("/api/user/$user->id");
        $response->assertStatus(200);

        $this->assertSoftDeleted(User::class, ['id' => $user->id]);

        $user->forceDelete();
    }
}
