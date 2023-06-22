<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\Bootstrap\ParameterStoreFactory;
use Cspray\AnnotatedContainer\ContainerFactory\ParameterStore;
use Cspray\AnnotatedContainer\Secrets\Exception\NoSourcesProvided;

final class ConfigParameterStoreFactory implements ParameterStoreFactory {

    public function __construct(
        private readonly IdentifierSourceMap $sourceMap
    ) {}

    /**
     * @param string $identifier
     * @return ParameterStore
     * @throws NoSourcesProvided
     */
    public function createParameterStore(string $identifier) : ParameterStore {
        $store = new ConfigParameterStore($identifier);
        $sources = $this->sourceMap->getSourcesForIdentifier($identifier);
        if ($sources === []) {
            throw NoSourcesProvided::fromNoSources($identifier);
        }
        foreach ($sources as $source) {
            $store->addSource($source);
        }

        return $store;
    }

}
