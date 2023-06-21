<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

use Cspray\AnnotatedContainer\Profiles\ActiveProfiles;
use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeIntersect;
use Cspray\Typiphy\TypeUnion;

final class ProfileAwareSource implements Source {

    /**
     * @param non-empty-string $name
     * @param non-empty-array<non-empty-string, ValueProvider> $profileValueProviderMap
     */
    public function __construct(
        private readonly string $name,
        private readonly ActiveProfiles $activeProfiles,
        private readonly array $profileValueProviderMap
    ) {}

    public function getName() : string {
        return $this->name;
    }

    public function getValue(TypeUnion|Type|TypeIntersect $type, string $key) : mixed {
        foreach ($this->profileValueProviderMap as $profile => $valueProvider) {
            if ($this->activeProfiles->isActive($profile)) {
                return $valueProvider->getValue($type, $key);
            }
        }

        return null;
    }
}