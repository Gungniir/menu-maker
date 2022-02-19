<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use Carbon\Carbon;
use Exception;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Contracts\TypeConvertible;

class DateTimeScalar extends ScalarType implements TypeConvertible
{
    /**
     * @var string
     */
    public $name = 'DateTimeScalar';

    /**
     * @var string
     */
    public $description = '';

    /**
     * Serializes an internal value to include in a response.
     *
     * @param Carbon $value
     *
     * @return string
     */
    public function serialize($value): string
    {
        return $value->format('Y.m.d H:i:s');
    }

    /**
     * Parses an externally provided value (query variable) to use as an input.
     *
     * In the case of an invalid value this method must throw an Exception
     *
     * @param string $value
     *
     * @return Carbon
     *
     * @throws Error
     */
    public function parseValue($value): Carbon
    {
        $date = Carbon::createFromFormat('Y.m.d H:s:i', $value);

        if (!is_string($value) || !$date) {
            throw Error::createLocatedError('Wrong DateTime format');
        }

        return $date;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * In the case of an invalid node or value this method must throw an Exception
     *
     * @param Node $valueNode
     * @param array|null $variables
     *
     * @return Carbon
     *
     * @throws Exception
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): Carbon
    {
        if ($valueNode->kind !== NodeKind::STRING) {
            throw Error::createLocatedError('Wrong DateTime format');
        }

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $date = Carbon::createFromFormat('Y.m.d H:s:i', $valueNode->value);

        if (!$date) {
            throw Error::createLocatedError('Wrong DateTime format');
        }

        return $date;
    }

    public function toType(): Type
    {
        return new static();
    }
}
