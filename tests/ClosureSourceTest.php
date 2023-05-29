<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ClosureSource;
use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\Typiphy\stringType;

#[CoversClass(ClosureSource::class)]
final class ClosureSourceTest extends TestCase {

    public function testSubjectGetNamePassedToConstructor() : void {
        $subject = new ClosureSource('closure-source', function() {});

        self::assertSame('closure-source', $subject->getName());
    }

    public function testSubjectGetValueDelegatesToClosure() : void {
        $subject = new ClosureSource('other-source', fn(Type|TypeIntersect|TypeUnion $type, string $key) : mixed => $type->getName() . '|' . $key);

        self::assertSame(
            'string|foo',
            $subject->getValue(stringType(), 'foo')
        );
    }

}