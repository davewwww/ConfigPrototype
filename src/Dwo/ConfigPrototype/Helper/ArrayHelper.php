<?php

namespace Dwo\ConfigPrototype\Helper;

use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class ArrayHelper
 *
 * @author David Wolter <david@lovoo.com>
 */
class ArrayHelper
{
    /**
     * from ('foo.bar.lorem.ipsum', ['foo'=>'bar'])
     * to {'foo':{'bar':{'lorem':{'ipsum':['foo':'bar']}}}}
     *
     * @param string $path
     * @param array  $body
     *
     * @return array
     */
    public static function buildDeepArrayWithBody($path, $body)
    {
        $deepArray = ArrayHelper::buildDeepArray($path);

        (new PropertyAccessor())->setValue($deepArray, self::buildDeepKey($path), $body);

        return $deepArray;
    }

    /**
     * from 'foo.bar.lorem.ipsum'
     * to {'foo':{'bar':{'lorem':{'ipsum':[]}}}}
     *
     * @param string|array $path
     * @param int          $index
     * @param array        $deepArray
     *
     * @return array
     */
    public static function buildDeepArray($path, $index = 0, array $deepArray = array())
    {
        if (is_string($path)) {
            $path = explode('.', $path);
        }

        if (isset($path[$index])) {
            $key = $path[$index];

            $deepArray[$key] = self::buildDeepArray(
                $path,
                $index + 1,
                isset($deepArray[$key]) ? $deepArray[$key] : array()
            );
        }

        return $deepArray;
    }

    /**
     * from 'foo.bar.lorem.ipsum'
     * to '[foo][bar][lorem][ipsum]'
     *
     * @param string $configKey
     *
     * @return string
     */
    public static function buildDeepKey($path)
    {
        return '['.implode('][', explode('.', $path)).']';
    }
}