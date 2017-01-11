<?php

namespace BrocardJr\Geo;

class GeoResult
{
    /**
     * Holds array elements of a result
     * @var array
     */
    protected $_vars = [];

    /**
     *
     * Class constructor
     * @param array $props
     */
    public function __construct($props)
    {
        if (is_array($props))
            $this->_vars = $props;
    }

    /**
     *
     * Returns a property value. Do not call this method. This is a PHP magic method
     * overrided to allow using the following syntax
     * <pre>
     * 	$egeoresult->propertyName;
     * </pre>
     * @param string $name the property name
     * @throws \RuntimeException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_vars))
            return $this->_vars[$name];

        $msg = 'Property "'.get_class($this).'.'.$name.'" is not defined.';
        throw new \RuntimeException($msg);
    }

    /**
     *
     * Checks if a property value is null.
     * Do not call this method. This is a PHP magic method that we override
     * to allow using isset() to detect if a component property is set or not.
     * @param string $name the property to check
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_vars[$name]);
    }
}