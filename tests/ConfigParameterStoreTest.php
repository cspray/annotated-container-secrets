<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\Exception\InvalidSecretsKey;
use Cspray\AnnotatedContainer\Secrets\ConfigParameterStore;
use Cspray\AnnotatedContainer\Secrets\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Cspray\AnnotatedContainer\Reflection\types;

#[
    CoversClass(ConfigParameterStore::class),
    CoversClass(InvalidSecretsKey::class)
]
final class ConfigParameterStoreTest extends TestCase {

    public function testGetNameReturnsValuePassedToConstructor() : void {
        $subject = new ConfigParameterStore('secrets');

        self::assertSame('secrets', $subject->name());
    }

    public static function storeDelimiterProvider() : array {
        return [
            ['.'],
            ['/'],
            ['$']
        ];
    }

    #[DataProvider('storeDelimiterProvider')]
    public function testFetchValueWithoutSourceDelimiterThrowsException(string $storeDelimiter) : void {
        $subject = new ConfigParameterStore('secrets', storeNameDelimiter: $storeDelimiter);

        self::expectException(InvalidSecretsKey::class);
        self::expectExceptionMessage(
            'The key "foo" passed to ' . ConfigParameterStore::class . ' MUST contain at least one "' . $storeDelimiter . '" delimiter.'
        );

        $subject->fetch(types()->string(), 'foo');
    }

    #[DataProvider('storeDelimiterProvider')]
    public function testFetchValueWithDelimiterAndNoSourcePresentThrowsException(string $storeDelimiter) : void {
        $subject = new ConfigParameterStore('secrets', storeNameDelimiter: $storeDelimiter);

        self::expectException(InvalidSecretsKey::class);
        self::expectExceptionMessage(sprintf(
            'The key "foo%sbar" specifies a secrets source "foo" that has not been added to ' . ConfigParameterStore::class, $storeDelimiter
        ));

        $subject->fetch(types()->string(), sprintf('foo%sbar', $storeDelimiter));
    }

    #[DataProvider('storeDelimiterProvider')]
    public function testFetchValueWithDelimiterAndSourcePresentReturnsValueFromNamedSource(string $storeDelimiter) : void {
        $subject = new ConfigParameterStore('secrets', storeNameDelimiter: $storeDelimiter);

        $source = $this->getMockBuilder(Source::class)->getMock();
        $source->expects($this->once())
            ->method('name')
            ->willReturn('foo');

        $subject->addSource($source);

        $source->expects($this->once())
            ->method('getValue')
            ->with(types()->string(), 'bar')
            ->willReturn('bar value');

        self::assertSame('bar value', $subject->fetch(types()->string(), sprintf('foo%sbar', $storeDelimiter)));
    }

}