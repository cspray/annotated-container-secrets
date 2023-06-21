<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;

final class SingleValueProviderSource implements Source {

    /**
     * @param non-empty-string $name
     * @param ValueProvider $valueProvider
     */
    public function __construct(
        private readonly string $name,
        private readonly ValueProvider $valueProvider
    ) {}

    public function getName() : string {
        return $this->name;
    }

    public function getValue(TypeUnion|Type|TypeIntersect $type, string $key) : mixed {
        return $this->valueProvider->getValue($type, $key);
    }
}