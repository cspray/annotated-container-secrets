<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;

interface ValueProvider {

    /**
     * @param non-empty-string $key
     */
    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed;

}