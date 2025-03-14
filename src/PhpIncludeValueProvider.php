<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Closure;
use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Secrets\Exception\InvalidPhpSourceFile;

final class PhpIncludeValueProvider implements ValueProvider {

    private readonly ValueProvider $valueProvider;

    public function __construct(
        string $filePath
    ) {
        $this->valueProvider = $this->valueProviderForIncludedFile($filePath);
    }

    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
        return $this->valueProvider->getValue($type, $key);
    }

    private function valueProviderForIncludedFile(string $filePath) : ValueProvider {
        if (!is_file($filePath)) {
            throw InvalidPhpSourceFile::fromPathNotFile($filePath);
        }
        $data = include $filePath;
        if (is_array($data)) {
            return new ArrayValueProvider($data);
        } else if ($data instanceof Closure) {
            return new ClosureValueProvider($data);
        } else if ($data instanceof ValueProvider) {
            return $data;
        } else {
            throw InvalidPhpSourceFile::fromPhpFileReturnedInvalidType($filePath);
        }
    }
}