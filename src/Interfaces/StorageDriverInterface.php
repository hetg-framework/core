<?php

namespace Hetg\Framework\Interfaces;


use Hetg\Framework\Model\Model;

interface StorageDriverInterface
{
    /**
     * @param Model $model
     *
     * @throws \Throwable
     */
    public function create(Model $model): void ;

    /**
     * @param Model $model
     *
     * @throws \Throwable
     */
    public function update(Model $model): void;

    /**
     * @param Model $model
     *
     * @throws \Throwable
     */
    public function delete(Model $model): void;

    /**
     * @param array  $params
     *
     * @return Model[]|Model|null
     */
    public function find(array $params);



}