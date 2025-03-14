<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\SingleValueProviderSource;
use Cspray\AnnotatedContainer\Secrets\ValueProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\AnnotatedContainer\Reflection\types;

#[CoversClass(SingleValueProviderSource::class)]
final class SingleValueProviderSourceTest extends TestCase {

    public function testNamePassedToConstructorReturnedFromGetName() : void {
        $subject = new SingleValueProviderSource(
            'source-name',
            $this->getMockBuilder(ValueProvider::class)->getMock()
        );

        self::assertSame('source-name', $subject->name());
    }

    public function testGetValueDelegatedToValueProviderInjectedInConstructor() : void {
        $subject = new SingleValueProviderSource(
            'source-name',
            $valueProvider = $this->getMockBuilder(ValueProvider::class)->getMock()
        );

        $valueProvider->expects($this->once())
            ->method('getValue')
            ->with(types()->string(), 'key')
            ->willReturn('something');

        self::assertSame('something', $subject->getValue(types()->string(), 'key'));
    }

}