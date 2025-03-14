<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Adbar\Dot;
use Cspray\AnnotatedContainer\Secrets\Exception\InvalidJsonSourceFile;
use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;
use JsonException;
use function dot;

final class JsonFileValueProvider implements ValueProvider {

    private readonly Dot $data;

    /**
     * @param non-empty-string $filePath
     * @throws InvalidJsonSourceFile
     */
    public function __construct(
        string $filePath
    ) {
        if (!is_file($filePath)) {
            throw InvalidJsonSourceFile::fromFileNotFound($filePath);
        }

        if (!is_readable($filePath)) {
            throw InvalidJsonSourceFile::fromFileNotReadable($filePath);
        }

        try {
            $contents = file_get_contents($filePath);
            assert($contents !== false);
            /**
             * @var mixed $json
             */
            $json = json_decode($contents, associative: true, flags: JSON_THROW_ON_ERROR);

            // Check if it is an array to account for non-array, non-object JSON values that might still be decodeable
            // Check for a non-empty array list because we don't wanna use integer keys
            if (!is_array($json) || array_is_list($json) && !empty($json)) {
                throw InvalidJsonSourceFile::fromFileNotJsonObject($filePath);
            }

            $this->data = dot($json);
        } catch (JsonException $exception) {
            throw InvalidJsonSourceFile::fromFileNotValidJson($filePath, $exception);
        }
    }

    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
        return $this->data->get($key);
    }

}
