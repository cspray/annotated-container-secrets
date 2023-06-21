<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Exception;

use Cspray\AnnotatedContainer\Exception\Exception;

final class NoSourcesProvided extends Exception {

    public static function fromNoSources() : self {
        return new self('At least 1 secrets source MUST be provided to this ParameterStore factory.');
    }

}
