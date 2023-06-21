<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ArrayValueProvider;
use Cspray\AnnotatedContainer\Secrets\Exception\InvalidParameterStoreIdentifier;
use Cspray\AnnotatedContainer\Secrets\Exception\NoSourcesProvided;
use Cspray\AnnotatedContainer\Secrets\SecretsParameterStore;
use Cspray\AnnotatedContainer\Secrets\SecretsParameterStoreFactory;
use Cspray\AnnotatedContainer\Secrets\SingleValueProviderSource;
use Cspray\AnnotatedContainer\Secrets\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\Typiphy\stringType;

#[
    CoversClass(SecretsParameterStoreFactory::class),
    CoversClass(SecretsParameterStore::class),
    CoversClass(InvalidParameterStoreIdentifier::class),
    CoversClass(NoSourcesProvided::class),
    CoversClass(ArrayValueProvider::class),
    CoversClass(SingleValueProviderSource::class),
]
final class SecretsParameterStoreFactoryTest extends TestCase {

    public function testFactoryCreatesInstancesOfSecretsParameterStore() : void {
        $subject = new SecretsParameterStoreFactory(
            [$this->getMockBuilder(Source::class)->getMock()]
        );

        self::assertInstanceOf(
            SecretsParameterStore::class,
            $subject->createParameterStore(SecretsParameterStore::class)
        );
    }

    public function testFactoryGivenNonSecretsParameterStoreIdentifierThrowsException() : void {
        $subject = new SecretsParameterStoreFactory(
            [$this->getMockBuilder(Source::class)->getMock()]
        );
        $this->expectException(InvalidParameterStoreIdentifier::class);
        $this->expectExceptionMessage('Attempted to create a parameter store with identifier, "NotSecrets", that this factory cannot create.');

        $subject->createParameterStore('NotSecrets');
    }

    public function testFactoryConstructorGivenNoSourcesThrowsException() : void {
        $this->expectException(NoSourcesProvided::class);
        $this->expectExceptionMessage('At least 1 secrets source MUST be provided to this ParameterStore factory.');

        new SecretsParameterStoreFactory([]);
    }

    public function testSourcesAreProvidedToSecretsParameterStore() : void {
        $subject = new SecretsParameterStoreFactory(
            [new SingleValueProviderSource('source', new ArrayValueProvider(['foo' => 'bar']))]
        );

        $store = $subject->createParameterStore(SecretsParameterStore::class);

        self::assertSame('bar', $store->fetch(stringType(), 'source.foo'));
    }

}
