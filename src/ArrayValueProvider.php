<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Adbar\Dot;
use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;
use function dot;

final class ArrayValueProvider implements ValueProvider {

    private readonly Dot $data;

    /**
     * @param array<non-empty-string, mixed> $data
     */
    public function __construct(
        array $data
    ) {
        $this->data = dot($data);
    }

    /**
     * @param non-empty-string $key
     */
    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
        return $this->data->get($key);
    }
}