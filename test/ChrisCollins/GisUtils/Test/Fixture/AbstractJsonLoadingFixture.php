<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Fixture;

use InvalidArgumentException;

/**
 * AbstractJsonLoadingFixture
 *
 * Abstract class to provide fixture that loads JSON from a file on disk.
 */
abstract class AbstractJsonLoadingFixture
{
    /**
     * Get some test JSON from a file.
     *
     * @param string $fileName The file name.
     *
     * @throws InvalidArgumentException If the file does not exist.
     */
    public function getJsonFromFile(string $fileName): string
    {
        $directory = $this->getJsonDirectory();

        $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException('JSON fixture "' . $filePath . '" does not exist.');
        }

        /** @var string */
        return file_get_contents($filePath);
    }

    abstract protected function getJsonDirectory(): string;
}
