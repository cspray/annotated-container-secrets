<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Exception;

use Cspray\AnnotatedContainer\Exception\Exception;
use JsonException;

final class InvalidJsonSourceFile extends Exception {

    public static function fromFileNotFound(string $path) : self {
        return new self(sprintf(
            'The file "%s" could not be found.',
            $path
        ));
    }

    public static function fromFileNotReadable(string $path) : self {
        return new self(sprintf(
            'The file "%s" could not be read.',
            $path
        ));
    }

    public static function fromFileNotValidJson(string $path, JsonException $exception) : self {
        return new self(sprintf(
            'The file "%s" is not valid JSON.', $path
        ), previous: $exception);
    }

    public static function fromFileNotJsonObject(string $path) : self {
        return new self(sprintf(
            'The file "%s" is not a JSON object.',
            $path
        ));
    }

}