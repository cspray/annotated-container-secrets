<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Adbar\Dot;
use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;
use function dot;

final class ArraySource implements Source {

    private readonly Dot $data;

    /**
     * @param non-empty-string $name
     * @param array<non-empty-string, mixed> $data
     */
    public function __construct(
        private readonly string $name,
        array $data
    ) {
        $this->data = dot($data);
    }

    /**
     * @return non-empty-string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @param non-empty-string $key
     */
    public function getValue(TypeUnion|Type|TypeIntersect $type, string $key) : mixed {
        return $this->data->get($key);
    }
}