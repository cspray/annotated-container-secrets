<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ArrayValueProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\Typiphy\stringType;

#[CoversClass(ArrayValueProvider::class)]
final class ArrayValueProviderTest extends TestCase {

    public function testEmptyArrayReturnsNull() : void {
        $subject = new ArrayValueProvider([]);

        self::assertNull($subject->getValue(stringType(), 'some-key'));
    }

    public function testSingleDimensionArrayWithKeyHasCorrectValue() : void {
        $subject = new ArrayValueProvider(['foo' => 'bar']);

        self::assertSame('bar', $subject->getValue(stringType(), 'foo'));
    }

    public function testMultiDimensionalArrayWithKeyHasCorrectValue() : void {
        $subject = new ArrayValueProvider([
            'foo' => [
                'bar' => [
                    'baz' => 'qux'
                ]
            ]
        ]);

        self::assertSame('qux', $subject->getValue(stringType(), 'foo.bar.baz'));
    }

}
