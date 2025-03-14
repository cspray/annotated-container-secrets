<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;

interface ValueProvider {

    /**
     * @param non-empty-string $key
     */
    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed;

}