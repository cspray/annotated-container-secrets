<?php declare(strict_types=1);

namespace Cspray\AnnotatedContainer\Secrets;

interface Source extends ValueProvider {

    /**
     * @return non-empty-string
     */
    public function getName() : string;

}
