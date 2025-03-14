<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ArrayValueProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\AnnotatedContainer\Reflection\types;

#[CoversClass(ArrayValueProvider::class)]
final class ArrayValueProviderTest extends TestCase {

    public function testEmptyArrayReturnsNull() : void {
        $subject = new ArrayValueProvider([]);

        self::assertNull($subject->getValue(types()->string(), 'some-key'));
    }

    public function testSingleDimensionArrayWithKeyHasCorrectValue() : void {
        $subject = new ArrayValueProvider(['foo' => 'bar']);

        self::assertSame('bar', $subject->getValue(types()->string(), 'foo'));
    }

    public function testMultiDimensionalArrayWithKeyHasCorrectValue() : void {
        $subject = new ArrayValueProvider([
            'foo' => [
                'bar' => [
                    'baz' => 'qux'
                ]
            ]
        ]);

        self::assertSame('qux', $subject->getValue(types()->string(), 'foo.bar.baz'));
    }

}
