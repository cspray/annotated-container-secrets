<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ClosureValueProvider;
use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\Typiphy\stringType;

#[CoversClass(ClosureValueProvider::class)]
final class ClosureValueProviderTest extends TestCase {

    public function testSubjectGetValueDelegatesToClosure() : void {
        $subject = new ClosureValueProvider(fn(Type|TypeIntersect|TypeUnion $type, string $key) : mixed => $type->getName() . '|' . $key);

        self::assertSame(
            'string|foo',
            $subject->getValue(stringType(), 'foo')
        );
    }

}