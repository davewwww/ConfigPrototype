<?php

namespace Dwo\ConfigPrototype\Handler;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class ConfigurationHandler
 *
 * @author David Wolter <david@lovoo.com>
 */
class ConfigurationHandler
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $name
     *
     * @return ConfigurationInterface
     *
     * @throws \Exception
     */
    public function getConfiguration($name)
    {
        if (!isset($this->config[$name]['configuration_class'])) {
            throw new \Exception(sprintf('Configuration for "%s" not found', $name));
        }
        $class = $this->config[$name]['configuration_class'];

        return new $class();
    }

    /**
     * @param string $name
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getPath($name)
    {
        if (!isset($this->config[$name]['prototype_path'])) {
            throw new \Exception(sprintf('Path for "%s" not found', $name));
        }

        return $this->config[$name]['prototype_path'];
    }
}