<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\ContainerFactory\ParameterStore;
use Cspray\AnnotatedContainer\Secrets\Exception\InvalidSecretsKey;
use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;

final class ConfigParameterStore implements ParameterStore {

    /**
     * @var array<non-empty-string, Source>
     */
    private array $sources = [];

    /**
     * @param non-empty-string $name
     * @param non-empty-string $storeNameDelimiter
     */
    public function __construct(
        private readonly string $name,
        private readonly string $storeNameDelimiter = '.'
    ) {}

    public function addSource(Source $source) : void {
        $this->sources[$source->getName()] = $source;
    }

    /**
     * @return non-empty-string
     */
    public function getName() : string {
        return $this->name;
    }

    public function fetch(TypeUnion|Type|TypeIntersect $type, string $key) : mixed {
        $sourceParts = explode($this->storeNameDelimiter, $key, 2);
        if (count($sourceParts) !== 2) {
            throw InvalidSecretsKey::fromKeyInvalidSourceDelimiter($key, $this->storeNameDelimiter);
        }

        /**
         * @var non-empty-string $source
         * @var non-empty-string $sourceKey
         */
        [$source, $sourceKey] = $sourceParts;

        if (!array_key_exists($source, $this->sources)) {
            throw InvalidSecretsKey::fromMissingSource($key, $source);
        }

        return $this->sources[$source]->getValue($type, $sourceKey);
    }
}