<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ClosureValueProvider;
use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\AnnotatedContainer\Reflection\types;

#[CoversClass(ClosureValueProvider::class)]
final class ClosureValueProviderTest extends TestCase {

    public function testSubjectGetValueDelegatesToClosure() : void {
        $subject = new ClosureValueProvider(fn(Type|TypeIntersect|TypeUnion $type, string $key) : mixed => $type->name() . '|' . $key);

        self::assertSame(
            'string|foo',
            $subject->getValue(types()->string(), 'foo')
        );
    }

}