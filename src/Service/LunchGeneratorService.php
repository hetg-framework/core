<?php

namespace Hetg\LunchGenerator\Service;


use Hetg\LunchGenerator\Interfaces\StorageDriverInterface;

/**
 * Class LunchGeneratorService
 *
 * @package Hetg\LunchGenerator\Service
 */
class LunchGenerator
{
    /**
     * @var StorageDriverInterface
     */
    private $driver;

    /**
     * LunchGeneratorService constructor.
     *
     * @param StorageDriverInterface $driver
     */
    public function __construct(StorageDriverInterface $driver)
    {
        $this->driver = $driver;
    }

}