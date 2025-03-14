<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;

final class SingleValueProviderSource implements Source {

    /**
     * @param non-empty-string $name
     * @param ValueProvider $valueProvider
     */
    public function __construct(
        private readonly string $name,
        private readonly ValueProvider $valueProvider
    ) {}

    public function name() : string {
        return $this->name;
    }

    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
        return $this->valueProvider->getValue($type, $key);
    }
}