<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets\Test;

use Cspray\AnnotatedContainer\Secrets\ArrayValueProvider;
use Cspray\AnnotatedContainer\Secrets\Exception\NoSourcesProvided;
use Cspray\AnnotatedContainer\Secrets\ConfigParameterStore;
use Cspray\AnnotatedContainer\Secrets\ConfigParameterStoreFactory;
use Cspray\AnnotatedContainer\Secrets\IdentifierSourceMap;
use Cspray\AnnotatedContainer\Secrets\SingleValueProviderSource;
use Cspray\AnnotatedContainer\Secrets\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function Cspray\AnnotatedContainer\Reflection\types;

#[
    CoversClass(ConfigParameterStoreFactory::class),
    CoversClass(ConfigParameterStore::class),
    CoversClass(NoSourcesProvided::class),
    CoversClass(ArrayValueProvider::class),
    CoversClass(SingleValueProviderSource::class),
    CoversClass(IdentifierSourceMap::class)
]
final class ConfigParameterStoreFactoryTest extends TestCase {

    public function testFactoryCreatesInstancesOfSecretsParameterStore() : void {
        $subject = new ConfigParameterStoreFactory(
            (new IdentifierSourceMap())
                ->withIdentifierAndSources(
                    'config',
                    [$this->getMockBuilder(Source::class)->getMock()]
                )
        );

        self::assertInstanceOf(
            ConfigParameterStore::class,
            $subject->createParameterStore('config')
        );
    }

    public function testFactoryGivenNonSecretsParameterStoreIdentifierThrowsException() : void {
        $subject = new ConfigParameterStoreFactory(new IdentifierSourceMap());
        $this->expectException(NoSourcesProvided::class);
        $this->expectExceptionMessage('No configuration sources were found for the parameter store identifier "NotSecrets".');

        $subject->createParameterStore('NotSecrets');
    }

    public function testSourcesAreProvidedToSecretsParameterStore() : void {
        $subject = new ConfigParameterStoreFactory(
            (new IdentifierSourceMap())
                ->withIdentifierAndSources(
                    'secrets',
                    [new SingleValueProviderSource('source', new ArrayValueProvider(['foo' => 'bar']))]
                )
        );
        $store = $subject->createParameterStore('secrets');
        self::assertInstanceOf(
            ConfigParameterStore::class,
            $store
        );
        self::assertSame('bar', $store->fetch(types()->string(), 'source.foo'));
    }

    public function testConfigParameterStoreNameIsIdentifierPassedIn() : void {
        $subject = new ConfigParameterStoreFactory(
            (new IdentifierSourceMap())
                ->withIdentifierAndSources(
                    'secrets',
                    [new SingleValueProviderSource('source', new ArrayValueProvider(['foo' => 'bar']))]
                )
        );
        $store = $subject->createParameterStore('secrets');

        self::assertSame('secrets', $store->name());
    }

}
