<?php

namespace Dwo\ConfigPrototype\Helper;

use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\BaseNode;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\EnumNode;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\PrototypedArrayNode;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class ConfigPrototypeBuilder
 *
 * @author David Wolter <david@lovoo.com>
 */
class ConfigPrototypeBuilder
{

    /**
     * @param string                 $path
     * @param array                  $prototype
     * @param ConfigurationInterface $configuration
     *
     * @return mixed
     */
    public static function createPrototype($path, $prototype, ConfigurationInterface $configuration)
    {
        $prototypeDeep = ArrayHelper::buildDeepArrayWithBody($path, ['new' => $prototype]);

        //create full config - incl. validation
        $tree = $configuration->getConfigTreeBuilder()->buildTree();
        $config = array($tree->getName() => (new Processor())->processConfiguration($configuration, $prototypeDeep));

        $accessor = PropertyAccess::createPropertyAccessor();
        $prototypeExtended = $accessor->getValue($config, ArrayHelper::buildDeepKey($path));

        return current($prototypeExtended);
    }

    /**
     * createEmptyPrototype('dwo_flagging.features', new Configuration())
     *
     * @param string                 $path
     * @param ConfigurationInterface $configuration
     *
     * @return array|null
     */
    public static function createEmptyPrototype($path, ConfigurationInterface $configuration)
    {
        $node = $configuration->getConfigTreeBuilder()->buildTree();

        $pathSplitted = explode('.', $path);
        unset($pathSplitted[0]);

        foreach ($pathSplitted as $name) {
            $node = $node->getChildren()[$name];
        }

        if (!$node instanceof PrototypedArrayNode) {
            return null;
        }
        $node = $node->getPrototype();
        $ar = array();
        if ($node instanceof PrototypedArrayNode) {
            $node = $node->getPrototype();
            $ar = array(array());
            self::walkPrototype($node, $ar[0]);
        } else {
            self::walkPrototype($node, $ar);
        }

        return $ar;
    }

    /**
     * @param BaseNode $node
     * @param array    $ar
     */
    private static function walkPrototype(BaseNode $node, array &$ar)
    {
        if ($node instanceof PrototypedArrayNode) {
            self::walkNode($node, '0', $ar);
        } elseif ($node instanceof ArrayNode) {
            foreach ($node->getChildren() as $k => $child) {
                self::walkNode($child, $k, $ar);
            }
        } else {
            $parent = $node->getParent();
            $name = $parent->getName();
            $ar[$name] = array();
            echo '['.$name.'] '.'*'.'<br>';
        }
        //return $array;
    }

    /**
     * @param BaseNode $node
     * @param string   $key
     * @param array    $ar
     */
    private static function walkNode(BaseNode $node, $key, &$ar)
    {
        $childName = $node->getName();

        if ($node instanceof PrototypedArrayNode) {
            $prototype = $node->getPrototype();

            if ($prototype instanceof PrototypedArrayNode) {
                if (empty($childName)) {
                    self::walkPrototype($prototype, $ar);
                } else {
                    $ar[$childName] = array();
                    self::walkPrototype($prototype, $ar[$childName]);
                }
            } elseif ($prototype instanceof ArrayNode) {
                if ($prototype->getParent() instanceof PrototypedArrayNode) {
                    $ar[$childName] = array(array());
                    self::walkPrototype($prototype, $ar[$childName][0]);
                } else {
                    $ar[$childName] = array();
                    self::walkPrototype($prototype, $ar[$childName]);
                }
            } else {
                $ar[$key] = array();
            }
        } elseif ($node instanceof ArrayNode) {
            $ar[$childName] = array();
            self::walkPrototype($node, $ar[$childName]);
        } else {
            $default = null;
            if ($node->hasDefaultValue()) {
                $default = $node->getDefaultValue();
            }
            if (null !== $example = $node->getExample()) {
                $default .= $example;
            }
            if ($node->isRequired()) {
                $default .= ' #required';
            }
            if ($node instanceof EnumNode) {
                $default .= ' #['.implode(', ', $node->getValues()).']';
            }

            $ar[$key] = $default;
        }
    }
}