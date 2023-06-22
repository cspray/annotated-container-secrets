<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Exception;

use Cspray\AnnotatedContainer\Exception\Exception;

final class NoSourcesProvided extends Exception {

    public static function fromNoSources(string $identifier) : self {
        return new self(sprintf(
            'No configuration sources were found for the parameter store identifier "%s".',
            $identifier
        ));
    }

}
