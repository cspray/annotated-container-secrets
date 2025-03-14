<?php declare(strict_types=1);

use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;

return function(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
    return match ($key) {
        'foo' => 'bar',
        'bar' => 42,
        default => null
    };
};
