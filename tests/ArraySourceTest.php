<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ArraySource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\Typiphy\stringType;

#[CoversClass(ArraySource::class)]
final class ArraySourceTest extends TestCase {

    public function testNamePassedToArraySourceReturnedFromGetName() : void {
        $subject = new ArraySource('source-name', []);

        self::assertSame('source-name', $subject->getName());
    }

    public function testEmptyArrayReturnsNull() : void {
        $subject = new ArraySource('source', []);

        self::assertNull($subject->getValue(stringType(), 'some-key'));
    }

    public function testSingleDimensionArrayWithKeyHasCorrectValue() : void {
        $subject = new ArraySource('source', ['foo' => 'bar']);

        self::assertSame('bar', $subject->getValue(stringType(), 'foo'));
    }

    public function testMultiDimensionalArrayWithKeyHasCorrectValue() : void {
        $subject = new ArraySource('source', [
            'foo' => [
                'bar' => [
                    'baz' => 'qux'
                ]
            ]
        ]);

        self::assertSame('qux', $subject->getValue(stringType(), 'foo.bar.baz'));
    }

}
