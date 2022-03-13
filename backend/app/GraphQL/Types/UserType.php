<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Privacy\MePrivacy;
use App\GraphQL\Scalars\DateTimeScalar;
use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A type',
        'model' => User::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'email' => [
                'type' => Type::string(),
                'privacy' => MePrivacy::class,
            ],
            'email_verified_at' => [
                'type' => GraphQL::type('DateTimeScalar'),
                'privacy' => MePrivacy::class,
            ],
            'created_at' => [
                'type' => GraphQL::type('DateTimeScalar'),
            ],
            'updated_at' => [
                'type' => GraphQL::type('DateTimeScalar'),
                'privacy' => MePrivacy::class,
            ],
            'telegram_id' => [
                'type' => Type::int(),
                'privacy' => MePrivacy::class,
            ],
            'dishes' => [
                'type' => Type::nonNull(Type::listOf(GraphQL::type('Dish')))
            ]
        ];
    }
}
