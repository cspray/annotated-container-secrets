<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ProfileAwareSource;
use Cspray\AnnotatedContainer\Secrets\ValueProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\Typiphy\intType;
use function Cspray\Typiphy\stringType;

#[CoversClass(ProfileAwareSource::class)]
final class ProfileAwareSourceTest extends TestCase {

    public function testNamePassedToConstructorReturnedFromGetName() : void {
        $subject = new ProfileAwareSource(
            'profile-aware',
            ['default'],
            [
                'dev' => $this->getMockBuilder(ValueProvider::class)->getMock()
            ]
        );

        self::assertSame('profile-aware', $subject->getName());
    }

    public function testProfileMapDoesNotHaveActiveProfileReturnsNull() : void {
        $subject = new ProfileAwareSource(
            'omg-thats-right',
            ['dev'],
            [
                'prod' => $valueProvider = $this->getMockBuilder(ValueProvider::class)->getMock()
            ]
        );

        $valueProvider->expects($this->never())->method('getValue');

        self::assertNull($subject->getValue(stringType(), 'key'));
    }

    public function testValueProviderMapWithActiveProfileHasValueReturned() : void {
        $subject = new ProfileAwareSource(
            'archer',
            ['dev'],
            [
                'prod' => $prodValueProvider = $this->getMockBuilder(ValueProvider::class)->getMock(),
                'dev' => $devValueProvider = $this->getMockBuilder(ValueProvider::class)->getMock(),
                'test' => $testValueProvider = $this->getMockBuilder(ValueProvider::class)->getMock()
            ]
        );

        $prodValueProvider->expects($this->never())->method('getValue');
        $devValueProvider->expects($this->once())
            ->method('getValue')
            ->with(intType(), 'meaningOfLife')
            ->willReturn(42);
        $testValueProvider->expects($this->never())->method('getValue');

        self::assertSame(42, $subject->getValue(intType(), 'meaningOfLife'));
    }

}