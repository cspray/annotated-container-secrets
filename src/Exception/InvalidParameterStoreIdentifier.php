<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Exception;

use Cspray\AnnotatedContainer\Exception\Exception;

final class InvalidParameterStoreIdentifier extends Exception {

    public static function fromIdentifierNotSecretsParameterStore(string $store) : self {
        return new self(sprintf(
            'Attempted to create a parameter store with identifier, "%s", that this factory cannot create.',
            $store
        ));
    }

}