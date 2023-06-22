<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\IdentifierSourceMap;
use Cspray\AnnotatedContainer\Secrets\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(IdentifierSourceMap::class)]
final class IdentifierSourceMapTest extends TestCase {

    public function testWithIdentifierAndSourcesReturnsNewInstance() : void {
        $a = new IdentifierSourceMap();
        $b = $a->withIdentifierAndSources('source', [$this->getMockBuilder(Source::class)->getMock()]);

        self::assertNotSame($a, $b);
    }

    public function testGetSourcesForIdentifierNotPresentReturnsEmptyArray() : void {
        $subject = new IdentifierSourceMap();

        self::assertSame([], $subject->getSourcesForIdentifier('not present'));
    }

    public function testGetSourcesWithIdentifierAndSourcesAdded() : void {
        $subject = new IdentifierSourceMap();
        $subject = $subject->withIdentifierAndSources('config store', $expected = [$this->getMockBuilder(Source::class)->getMock()]);

        self::assertSame($expected, $subject->getSourcesForIdentifier('config store'));
    }

}