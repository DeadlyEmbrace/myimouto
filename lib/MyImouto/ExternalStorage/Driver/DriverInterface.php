<?php
namespace MyImouto\ExternalStorage\Driver;

use MyImouto\ExternalStorage\Relation;

interface DriverInterface
{
    /**
     * @return array
     */
    public function execute(Relation $relation);
}
