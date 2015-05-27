<?php

namespace Dwo\ConfigPrototype\Model;

/**
 * Interface ConfigPrototypeManagerInterface
 *
 * @author David Wolter <david@lovoo.com>
 */
interface ConfigPrototypeManagerInterface
{
    /**
     * @param string $name
     * @param string $type
     *
     * @return ConfigPrototypeInterface|null
     */
    public function findConfigPrototypeByNameAndType($name, $type);

    /**
     * @param ConfigPrototypeInterface $configPrototype
     */
    public function saveConfigPrototype(ConfigPrototypeInterface $configPrototype);
}
