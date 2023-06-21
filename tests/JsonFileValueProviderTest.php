<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\Exception\InvalidJsonSourceFile;
use Cspray\AnnotatedContainer\Secrets\JsonFileValueProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Json;
use function Cspray\Typiphy\arrayType;
use function Cspray\Typiphy\stringType;

#[
    CoversClass(JsonFileValueProvider::class),
    CoversClass(InvalidJsonSourceFile::class)
]
final class JsonFileValueProviderTest extends TestCase {

    private string $dir;

    protected function setUp() : void {
        $this->dir = __DIR__ . '/Fixture';
    }

    public function testPathIsNotFileThrowsException() : void {
        self::expectException(InvalidJsonSourceFile::class);
        self::expectExceptionMessage(
            sprintf('The file "%s/not-found.json" could not be found.', $this->dir)
        );

        new JsonFileValueProvider($this->dir . '/not-found.json');
    }

    public function testPathIsNotValidJsonThrowsException() : void {
        self::expectException(InvalidJsonSourceFile::class);
        self::expectExceptionMessage(
            sprintf('The file "%s/not-json.txt" is not valid JSON.', $this->dir)
        );

        new JsonFileValueProvider($this->dir . '/not-json.txt');
    }

    public function testPathIsNotObjectJsonThrowsException() : void {
        self::expectException(InvalidJsonSourceFile::class);
        self::expectExceptionMessage(
            sprintf('The file "%s/not-object-json.json" is not a JSON object.', $this->dir)
        );

        new JsonFileValueProvider($this->dir . '/not-object-json.json');
    }

    public function testSingleLevelJsonObjectReturnsCorrectValue() : void {
        $source = new JsonFileValueProvider($this->dir . '/single-level.json');

        self::assertSame('bar', $source->getValue(stringType(), 'foo'));
    }

    public function testEmptyObjectReturnsNull() : void {
        $source = new JsonFileValueProvider($this->dir . '/empty-object.json');

        self::assertNull($source->getValue(stringType(), 'foo'));
    }

    public function testNestedObjectReturnsCorrectValue() : void {
        $source = new JsonFileValueProvider($this->dir . '/nested-object.json');

        self::assertSame(
            ['qux', 'quy', 'quz'],
            $source->getValue(arrayType(), 'foo.bar.baz')
        );
    }

    public function testPathIsNotObjectOrArrayJsonThrowsException() : void {
        self::expectException(InvalidJsonSourceFile::class);
        self::expectExceptionMessage(
            sprintf('The file "%s/string.json" is not a JSON object.', $this->dir)
        );

        new JsonFileValueProvider($this->dir . '/string.json');
    }

}
