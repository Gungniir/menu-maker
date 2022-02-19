<?php

namespace App\GraphQL\Privacy;

use Rebing\GraphQL\Support\Privacy;

class MePrivacy extends Privacy
{
    /**
     * @inheritDoc
     */
    public function validate(array $queryArgs, $queryContext = null): bool
    {
        return isset($queryArgs['id']) && $queryArgs['id'] === auth()->user()->id;
    }
}
