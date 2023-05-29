<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\Exception\InvalidJsonSourceFile;
use Cspray\AnnotatedContainer\Secrets\JsonFileSource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Json;
use function Cspray\Typiphy\arrayType;
use function Cspray\Typiphy\stringType;

#[
    CoversClass(JsonFileSource::class),
    CoversClass(InvalidJsonSourceFile::class)
]
final class JsonFileSourceTest extends TestCase {

    private string $dir;

    protected function setUp() : void {
        $this->dir = __DIR__ . '/Fixture';
    }

    public function testNamePassedToConstructorReturnedFromGetName() : void {
        $subject = new JsonFileSource('json-name', $this->dir . '/single-level.json');

        self::assertSame('json-name', $subject->getName());
    }

    public function testPathIsNotFileThrowsException() : void {
        self::expectException(InvalidJsonSourceFile::class);
        self::expectExceptionMessage(
            sprintf('The file "%s/not-found.json" could not be found.', $this->dir)
        );

        new JsonFileSource('json-name', $this->dir . '/not-found.json');
    }

    public function testPathIsNotValidJsonThrowsException() : void {
        self::expectException(InvalidJsonSourceFile::class);
        self::expectExceptionMessage(
            sprintf('The file "%s/not-json.txt" is not valid JSON.', $this->dir)
        );

        new JsonFileSource('not-json', $this->dir . '/not-json.txt');
    }

    public function testPathIsNotObjectJsonThrowsException() : void {
        self::expectException(InvalidJsonSourceFile::class);
        self::expectExceptionMessage(
            sprintf('The file "%s/not-object-json.json" is not a JSON object.', $this->dir)
        );

        new JsonFileSource('not-json-object', $this->dir . '/not-object-json.json');
    }

    public function testSingleLevelJsonObjectReturnsCorrectValue() : void {
        $source = new JsonFileSource('single-level-object', $this->dir . '/single-level.json');

        self::assertSame('bar', $source->getValue(stringType(), 'foo'));
    }

    public function testEmptyObjectReturnsNull() : void {
        $source = new JsonFileSource('empty-object', $this->dir . '/empty-object.json');

        self::assertNull($source->getValue(stringType(), 'foo'));
    }

    public function testNestedObjectReturnsCorrectValue() : void {
        $source = new JsonFileSource('nested-object', $this->dir . '/nested-object.json');

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

        new JsonFileSource('string-json', $this->dir . '/string.json');
    }

}
