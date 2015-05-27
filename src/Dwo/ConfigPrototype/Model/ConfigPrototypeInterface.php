<?php

namespace Dwo\ConfigPrototype\Model;

/**
 * Interface ConfigPrototypeInterface
 *
 * @author David Wolter <david@lovoo.com>
 */
interface ConfigPrototypeInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return array
     */
    public function getContent();

    /**
     * @param array $content
     */
    public function setContent(array $content);

}
