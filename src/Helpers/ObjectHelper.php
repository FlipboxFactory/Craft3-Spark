<?php

/**
 * @package    Spark
 * @author     Flipbox Factory <hello@flipboxfactory.com>
 * @copyright  2010-2016 Flipbox Digital Limited
 * @license    https://github.com/FlipboxFactory/Craft3-Spark/blob/master/LICENSE
 * @link       https://github.com/FlipboxFactory/Craft3-Spark
 * @since      Class available since Release 1.0.0
 */

namespace Flipbox\Craft3\Spark\Helpers;

use Flipbox\Craft3\Spark\Exceptions\InvalidConfigurationException;
use Flipbox\Craft3\Spark\Objects\Interfaces\ObjectInterface;

class ObjectHelper
{

    /**
     * Returns the public member variables of an object.
     *
     * @param ObjectInterface $object
     * @return array
     */
    public static function getObjectVars(ObjectInterface $object)
    {
        return get_object_vars($object);
    }

    /**
     * Configures an object with the initial property values.
     *
     * @param ObjectInterface $object
     * @param $properties
     * @return ObjectInterface
     */
    public static function configure(ObjectInterface $object, $properties)
    {

        // Populate model attributes
        if (is_array($properties)) {

            foreach ($properties as $name => $value) {

                if ($object->canSetProperty($name)) {
                    $object->$name = $value;
                }

            }

        }

        return $object;

    }

    /**
     * Create a new object
     *
     * @param $config
     * @param null $instanceOf
     * @return ObjectInterface
     * @throws InvalidConfigurationException
     */
    public static function create($config, $instanceOf = null)
    {

        // Get class from config
        $class = static::checkConfig($config, $instanceOf);

        // New object
        $object = new $class();

        // Configure
        if($config) {
            static::configure($object, $config);
        }

        return $object;

    }

    /**
     * Checks the config for a valid class
     *
     * @param $config
     * @param null $instanceOf
     * @param bool $removeClass
     * @return null|string
     * @throws InvalidConfigurationException
     */
    public static function checkConfig(&$config, $instanceOf = null, $removeClass = true)
    {

        // Get class from config
        $class = static::getClassFromConfig($config, $removeClass);

        // Make sure we have a valid class
        if ($instanceOf && !is_subclass_of($class, $instanceOf)) {

            throw new InvalidConfigurationException(
                sprintf(
                    "The class '%s' must be an instance of '%s'",
                    (string)$class,
                    (string)$instanceOf
                )
            );
        }

        return $class;

    }

    /**
     * Get a class from a config
     *
     * @param $config
     * @param bool $removeClass
     * @return string
     * @throws InvalidConfigurationException
     */
    public static function getClassFromConfig(&$config, $removeClass = false)
    {

        // Find class
        $class = static::findClassFromConfig($config, $removeClass);

        if (empty($class)) {
            throw new InvalidConfigurationException(
                sprintf(
                    "The configuration must specify a 'class' property: '%s'",
                    JsonHelper::encode($config)
                )
            );
        }

        return $class;

    }

    /**
     * Find a class from a config
     *
     * @param $config
     * @param bool $removeClass
     * @return null|string
     */
    public static function findClassFromConfig(&$config, $removeClass = false)
    {

        // Normalize the config
        if (is_string($config)) {

            // Set as class
            $class = $config;

            // Clear class from config
            $config = '';

        } elseif (is_object($config)) {

            return get_class($config);

        } else {

            // Force Array
            if (!is_array($config)) {

                $config = ArrayHelper::toArray($config, [], false);

            }

            if ($removeClass) {

                if (!$class = ArrayHelper::remove($config, 'class')) {

                    $class = ArrayHelper::remove($config, 'type');

                }

            } else {

                $class = ArrayHelper::getValue(
                    $config,
                    'class',
                    ArrayHelper::getValue($config, 'type')
                );

            }

        }

        return $class;

    }

}
