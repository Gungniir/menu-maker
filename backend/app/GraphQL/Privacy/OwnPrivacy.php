<?php

namespace App\GraphQL\Privacy;

class OwnPrivacy extends \Rebing\GraphQL\Support\Privacy
{
    /**
     * @inheritDoc
     */
    public function validate(array $queryArgs, $queryContext = null): bool
    {
        return $queryArgs['creator_id'] === auth()->user()->id;
    }
}
