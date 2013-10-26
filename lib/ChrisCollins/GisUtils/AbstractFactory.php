<?php

namespace ChrisCollins\GisUtils;

use \InvalidArgumentException;

/**
 * AbstractFactory
 *
 * An abstract class for factories.
 */
abstract class AbstractFactory
{
    /**
     * @var array Array of static data for creating objects.
     */
    protected static $data = array();

    /**
     * Factory method.
     *
     * @param string $name The name of the object to create.
     *
     * @return Object The created object.
     *
     * @throws InvalidArgumentException If the $name cannot be created.
     */
    public function create($name)
    {
        $data = $this->getData($name);

        return $this->createFromData($name, $data);
    }

    /**
     * Create a new instance of the object this factory creates, from the given data.
     *
     * @param string The name of the object to create.
     * @param array $data An array of data to create the object from.
     *
     * @return Object An Object.
     */
    abstract protected function createFromData($name, array $data);

    /**
     * Get the data required for creating an object.
     *
     * @param string $name The name of the object to create.
     *
     * @return array An array of data for creating the object.
     *
     * @throws InvalidArgumentException If the $name cannot be created.
     */
    protected function getData($name)
    {
        $ucName = strtoupper($name);

        if (!isset(static::$data[$ucName])) {
            $allowedObjectsCsv = implode(', ', array_keys(static::$data));

            throw new InvalidArgumentException(get_class($this) . ": {$name} is not supported ({$allowedObjectsCsv}).");
        }

        return static::$data[$ucName];
    }
}
