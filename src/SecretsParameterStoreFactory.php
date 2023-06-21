<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\Bootstrap\ParameterStoreFactory;
use Cspray\AnnotatedContainer\ContainerFactory\ParameterStore;
use Cspray\AnnotatedContainer\Secrets\Exception\InvalidParameterStoreIdentifier;
use Cspray\AnnotatedContainer\Secrets\Exception\NoSourcesProvided;

final class SecretsParameterStoreFactory implements ParameterStoreFactory {

    /**
     * @param list<Source> $sources
     */
    public function __construct(
        private readonly array $sources
    ) {
        if ($this->sources === []) {
            throw NoSourcesProvided::fromNoSources();
        }
    }

    public function createParameterStore(string $identifier) : ParameterStore {
        if ($identifier !== SecretsParameterStore::class) {
            throw InvalidParameterStoreIdentifier::fromIdentifierNotSecretsParameterStore($identifier);
        }
        $store = new SecretsParameterStore();
        foreach ($this->sources as $source) {
            $store->addSource($source);
        }

        return $store;
    }

}
