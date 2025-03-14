<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\Reflection\Type;
use Cspray\AnnotatedContainer\Reflection\TypeUnion;
use Cspray\AnnotatedContainer\Reflection\TypeIntersect;

final class ProfileAwareSource implements Source {

    /**
     * @param non-empty-string $name
     * @param non-empty-list<non-empty-string> $activeProfiles
     * @param non-empty-array<non-empty-string, ValueProvider> $profileValueProviderMap
     */
    public function __construct(
        private readonly string $name,
        private readonly array $activeProfiles,
        private readonly array $profileValueProviderMap
    ) {}

    public function name() : string {
        return $this->name;
    }

    public function getValue(Type|TypeUnion|TypeIntersect $type, string $key) : mixed {
        foreach ($this->profileValueProviderMap as $profile => $valueProvider) {
            if (in_array($profile, $this->activeProfiles, true)) {
                return $valueProvider->getValue($type, $key);
            }
        }

        return null;
    }
}