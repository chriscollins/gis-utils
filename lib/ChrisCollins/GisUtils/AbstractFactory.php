<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils;

use InvalidArgumentException;

/**
 * AbstractFactory
 *
 * An abstract class for factories.
 *
 * @template T of object
 */
abstract class AbstractFactory
{
    /** @var array<string,mixed> Array of static data for creating objects. */
    protected static $data = [];

    /**
     * Factory method.
     *
     * @param string $name The name of the object to create.
     *
     * @throws InvalidArgumentException If the $name cannot be created.
     *
     * @return T
     */
    public function create(string $name): object
    {
        $data = $this->getData($name);

        return $this->createFromData($name, $data);
    }

    /**
     * Create a new instance of the object this factory creates, from the given data.
     *
     * @param string $name The name of the object to create.
     * @param array<string,mixed> $data An array of data to create the object from.
     *
     * @return T
     */
    abstract protected function createFromData(string $name, array $data): object;

    /**
     * Get the data required for creating an object.
     *
     * @param string $name The name of the object to create.
     *
     * @throws InvalidArgumentException If the $name cannot be created.
     *
     * @return array<string,mixed> An array of data for creating the object.
     */
    protected function getData(string $name)
    {
        $ucName = strtoupper($name);

        if (!isset(static::$data[$ucName])) {
            $allowedObjectsCsv = implode(', ', array_keys(static::$data));

            throw new InvalidArgumentException(
                static::class . sprintf(': %s is not supported (%s).', $name, $allowedObjectsCsv)
            );
        }

        return static::$data[$ucName];
    }
}
