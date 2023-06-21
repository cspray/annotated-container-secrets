<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Exception;

use Cspray\AnnotatedContainer\Exception\Exception;

final class InvalidPhpSourceFile extends Exception {

    public static function fromPhpFileReturnedInvalidType(string $filePath) : self {
        return new self(sprintf(
            'The provided file "%s" did not return a valid type.',
            $filePath
        ));
    }

    public static function fromPathNotFile(string $filePath) : self {
        return new self(sprintf(
            'The provided path "%s" is not a valid file.',
            $filePath
        ));
    }

}