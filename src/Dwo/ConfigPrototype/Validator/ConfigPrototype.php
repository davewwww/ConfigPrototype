<?php

namespace Dwo\ConfigPrototype\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class ConfigPrototype
 *
 * @author David Wolter <david@lovoo.com>
 */
class ConfigPrototype extends Constraint
{
    /**
     * @var string
     */
    public $type;

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'config_prototype';
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
