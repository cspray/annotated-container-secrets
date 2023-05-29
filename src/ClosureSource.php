<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;
use Closure;

final class ClosureSource implements Source {

    /**
     * @param non-empty-string $name
     * @param Closure(Type|TypeUnion|TypeIntersect,string):mixed $closure
     */
    public function __construct(
        private readonly string $name,
        private readonly \Closure $closure
    ) {}

    public function getName() : string {
        return $this->name;
    }

    public function getValue(TypeUnion|Type|TypeIntersect $type, string $key) : mixed {
        return ($this->closure)($type, $key);
    }
}