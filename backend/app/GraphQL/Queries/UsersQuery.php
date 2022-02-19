<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Types\UserType;
use App\Models\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'users',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::listOf(GraphQL::type('User')));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ]
        ];
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     * @noinspection MissingParameterTypeDeclarationInspection
     */
    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, SelectFields $selectField): array|\Illuminate\Database\Eloquent\Collection|Collection
    {
        $select = $selectField->getSelect();
        $with = $selectField->getRelations();

        if (isset($args['id'])) {
            return User::whereId($args['id'])->with($with)->select($select)->get();
        }

        return User::with($with)->select($select)->get();
    }
}
