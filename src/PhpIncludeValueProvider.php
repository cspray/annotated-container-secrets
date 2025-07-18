<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Closure;
use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Secrets\Exception\InvalidPhpSourceFile;

final class PhpIncludeValueProvider implements ValueProvider {

    private readonly string $filePath;

    private ?ValueProvider $valueProvider;

    public function __construct(
        string $filePath
    ) {
        $this->filePath = $filePath;
    }

    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
        $this->valueProvider ??= $this->valueProviderForIncludedFile();
        return $this->valueProvider->getValue($type, $key);
    }

    private function valueProviderForIncludedFile() : ValueProvider {
        if (!is_file($this->filePath)) {
            throw InvalidPhpSourceFile::fromPathNotFile($this->filePath);
        }
        $data = include $this->filePath;
        if (is_array($data)) {
            return new ArrayValueProvider($data);
        } else if ($data instanceof Closure) {
            return new ClosureValueProvider($data);
        } else if ($data instanceof ValueProvider) {
            return $data;
        } else {
            throw InvalidPhpSourceFile::fromPhpFileReturnedInvalidType($this->filePath);
        }
    }
}