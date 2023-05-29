<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\Exception\InvalidSecretsKey;
use Cspray\AnnotatedContainer\Secrets\SecretsParameterStore;
use Cspray\AnnotatedContainer\Secrets\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Cspray\Typiphy\stringType;

#[
    CoversClass(SecretsParameterStore::class),
    CoversClass(InvalidSecretsKey::class)
]
final class SecretsParameterStoreTest extends TestCase {

    public function testGetNameReturnsValuePassedToConstructor() : void {
        $subject = new SecretsParameterStore();

        self::assertSame('secrets', $subject->getName());
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
        $subject = new SecretsParameterStore(storeNameDelimiter: $storeDelimiter);

        self::expectException(InvalidSecretsKey::class);
        self::expectExceptionMessage(
            'The key "foo" passed to ' . SecretsParameterStore::class . ' MUST contain at least one "' . $storeDelimiter . '" delimiter.'
        );

        $subject->fetch(stringType(), 'foo');
    }

    #[DataProvider('storeDelimiterProvider')]
    public function testFetchValueWithDelimiterAndNoSourcePresentThrowsException(string $storeDelimiter) : void {
        $subject = new SecretsParameterStore(storeNameDelimiter: $storeDelimiter);

        self::expectException(InvalidSecretsKey::class);
        self::expectExceptionMessage(sprintf(
            'The key "foo%sbar" specifies a secrets source "foo" that has not been added to ' . SecretsParameterStore::class, $storeDelimiter
        ));

        $subject->fetch(stringType(), sprintf('foo%sbar', $storeDelimiter));
    }

    #[DataProvider('storeDelimiterProvider')]
    public function testFetchValueWithDelimiterAndSourcePresentReturnsValueFromNamedSource(string $storeDelimiter) : void {
        $subject = new SecretsParameterStore(storeNameDelimiter: $storeDelimiter);

        $source = $this->getMockBuilder(Source::class)->getMock();
        $source->expects($this->once())
            ->method('getName')
            ->willReturn('foo');

        $subject->addSource($source);

        $source->expects($this->once())
            ->method('getValue')
            ->with(stringType(), 'bar')
            ->willReturn('bar value');

        self::assertSame('bar value', $subject->fetch(stringType(), sprintf('foo%sbar', $storeDelimiter)));
    }

}