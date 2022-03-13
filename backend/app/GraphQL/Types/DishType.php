<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Dish;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class DishType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Dish',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int())
            ],
            'creator_id' => [
                'type' => Type::nonNull(Type::int())
            ],
            'creator' => [
                'type' => GraphQL::type('User')
            ],
            'name' => [
                'type' => Type::nonNull(Type::string())
            ],
            'is_archive' => [
                'type' => Type::boolean(),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->is_archive : null;
                }
            ],
            'cooking_time' => [
                'type' => Type::int(),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->cooking_time : null;
                }
            ],
            'kcal' => [
                'type' => Type::int(),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->kcal : null;
                }
            ],
            'proteins' => [
                'type' => Type::int(),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->proteins : null;
                }
            ],
            'fats' => [
                'type' => Type::int(),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->fats : null;
                }
            ],
            'carbohydrates' => [
                'type' => Type::int(),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->carbohydrates : null;
                }
            ],
            'link' => [
                'type' => Type::string(),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->link : null;
                }
            ],
            'created_at' => [
                'type' => GraphQL::type('DateTimeScalar'),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->created_at : null;
                }
            ],
            'updated_at' => [
                'type' => GraphQL::type('DateTimeScalar'),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->updated_at : null;
                }
            ],
            'deleted_at' => [
                'type' => GraphQL::type('DateTimeScalar'),
                'resolve' => function (Dish $root) {
                    return $root->creator_id === auth()->user()->id ? $root->deleted_at : null;
                }
            ],
        ];
    }
}
