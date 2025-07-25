<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ArrayValueProvider;
use Cspray\AnnotatedContainer\Secrets\ClosureValueProvider;
use Cspray\AnnotatedContainer\Secrets\Exception\InvalidPhpSourceFile;
use Cspray\AnnotatedContainer\Secrets\PhpIncludeValueProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Cspray\AnnotatedContainer\Reflection\types;

#[
    CoversClass(PhpIncludeValueProvider::class),
    CoversClass(ArrayValueProvider::class),
    CoversClass(ClosureValueProvider::class),
    CoversClass(InvalidPhpSourceFile::class)
]
final class PhpIncludeValueProviderTest extends TestCase {

    public static function phpIncludeFiles() : array {
        return [
            [__DIR__ . '/Fixture/return-array.php'],
            [__DIR__ . '/Fixture/return-closure.php'],
            [__DIR__ . '/Fixture/return-value-provider.php']
        ];
    }

    #[DataProvider('phpIncludeFiles')]
    public function testValidTypesReturnedFromIncludedFileReturnsValueForFoundKey(string $file) : void {
        $valueProvider = new PhpIncludeValueProvider($file);

        self::assertSame('bar', $valueProvider->getValue(types()->string(), 'foo'));
        self::assertSame(42, $valueProvider->getValue(types()->int(), 'bar'));
        self::assertNull($valueProvider->getValue(types()->string(), 'baz'));
    }

    public function testInvalidTypeReturnedFromIncludedFileThrowsException() : void {
        $file = __DIR__ . '/Fixture/no-return.php';
        $this->expectException(InvalidPhpSourceFile::class);
        $this->expectExceptionMessage("The provided file \"$file\" did not return a valid type.");

        (new PhpIncludeValueProvider($file))->getValue(types()->string(), 'some-key');
    }

    public function testStringIsNotFilePathThrowsException() : void {
        $this->expectException(InvalidPhpSourceFile::class);
        $this->expectExceptionMessage("The provided path \"not a file path\" is not a valid file");

        (new PhpIncludeValueProvider('not a file path'))->getValue(types()->bool(), 'some-other-key');
    }

    public function testValuesAreLazyLoadedResultingInFileNotBeingIncludedUntilGettingValue() : void {
        $provider = new PhpIncludeValueProvider(__DIR__ . '/Fixture/exception-throwing.php');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Thrown from the included file');

        $provider->getValue(types()->array(), 'any-key');
    }

}