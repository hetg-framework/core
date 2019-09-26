<?php

namespace Hetg\Framework\Service;


use Hetg\Framework\Interfaces\StorageDriverInterface;

/**
 * Class LunchGeneratorService
 *
 * @package Hetg\Framework\Service
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