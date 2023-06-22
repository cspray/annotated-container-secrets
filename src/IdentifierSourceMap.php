<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

final class IdentifierSourceMap {

    /**
     * @var array<non-empty-string, list<Source>>
     */
    private array $map = [];

    public function __construct() {}

    /**
     * @param non-empty-string $identifier
     * @param list<Source> $sources
     */
    public function withIdentifierAndSources(string $identifier, array $sources) : self {
        $new = clone $this;
        $new->map[$identifier] = $sources;
        return $new;
    }

    /**
     * @param non-empty-string $identifier
     * @return list<Source>
     */
    public function getSourcesForIdentifier(string $identifier) : array {
        return $this->map[$identifier] ?? [];
    }


}