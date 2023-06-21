<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;
use Closure;

final class ClosureValueProvider implements ValueProvider {

    /**
     * @param Closure(Type|TypeUnion|TypeIntersect,string):mixed $closure
     */
    public function __construct(
        private readonly \Closure $closure
    ) {}

    public function getValue(TypeUnion|Type|TypeIntersect $type, string $key) : mixed {
        return ($this->closure)($type, $key);
    }
}