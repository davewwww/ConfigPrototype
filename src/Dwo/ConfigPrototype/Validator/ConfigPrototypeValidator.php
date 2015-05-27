<?php

namespace Dwo\ConfigPrototype\Validator;

use Dwo\ConfigPrototype\Handler\ConfigurationHandler;
use Dwo\ConfigPrototype\Helper\ConfigPrototypeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * Class ConfigPrototypeValidator
 *
 * @author David Wolter <david@lovoo.com>
 */
class ConfigPrototypeValidator extends ConstraintValidator
{
    /**
     * @var ConfigurationHandler
     */
    protected $configurationHandler;

    /**
     * @param ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
    }

    /**
     * @param mixed                      $value
     * @param Constraint|ConfigPrototype $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('value must be an array');
        }

        try {
            $path = $this->configurationHandler->getPath($constraint->type);
            $configuration = $this->configurationHandler->getConfiguration($constraint->type);

            ConfigPrototypeBuilder::createPrototype($path, $value, $configuration);
        } catch (InvalidConfigurationException $e) {
            $this->context->addViolation($e->getMessage());
        }
    }

}
