<?php

namespace Api\Links;

use ZF\Hal\Link\Link;

/**
 * Class FlipLink
 */
class FlipLink extends Link
{
    /**
     * FlipLink constructor.
     */
    public function __construct()
    {
        parent::__construct('flip');
        $this->setRoute('api.rest.flip');
    }
}
