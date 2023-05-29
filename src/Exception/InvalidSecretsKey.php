<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Exception;

use Cspray\AnnotatedContainer\Exception\Exception;
use Cspray\AnnotatedContainer\Secrets\SecretsParameterStore;

final class InvalidSecretsKey extends Exception {

    public static function fromKeyInvalidSourceDelimiter(string $key, string $storeDelimiter) : self {
        return new self(sprintf(
            'The key "%s" passed to %s MUST contain at least one "%s" delimiter.',
            $key,
            SecretsParameterStore::class,
            $storeDelimiter
        ));
    }

    public static function fromMissingSource(string $key, string $source) : self {
        return new self(sprintf(
            'The key "%s" specifies a secrets source "%s" that has not been added to %s',
            $key,
            $source,
            SecretsParameterStore::class
        ));
    }

}