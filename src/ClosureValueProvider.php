<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;
use Closure;

final class ClosureValueProvider implements ValueProvider {

    public function __construct(
        private readonly Closure $closure
    ) {}

    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
        return ($this->closure)($type, $key);
    }
}