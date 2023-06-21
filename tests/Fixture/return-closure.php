<?php declare(strict_types=1);

use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;

return function(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
    return match ($key) {
        'foo' => 'bar',
        'bar' => 42,
        default => null
    };
};
