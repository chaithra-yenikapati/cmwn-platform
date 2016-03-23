<?php

namespace Import\Importer\Nyc\Parser;

use Zend\Validator\Regex;

/**
 * Class ClassValidator
 */
class ClassIdValidator extends Regex
{
    /**
     * ClassIdValidator constructor.
     * @param string|\Traversable $pattern
     */
    public function __construct($pattern = null)
    {
        parent::__construct('/^\d{3}$/');
    }

    public function isValid($value)
    {
        if (strpos($value, '8') === 0) {
            $this->pattern = '/^8\d{3}$/';
        }
        
        return parent::isValid($value); // TODO: Change the autogenerated stub
    }
}
