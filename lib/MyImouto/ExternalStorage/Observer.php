<?php
namespace MyImouto\ExternalStorage;

Rails\ActiveRecord\Observer\Observer as AbstractObserver;

class Observer extends AbstractObserver
{
    /**
     * MyImouto\ExternalStorage\Driver\DriverInterface
     */
    static protected $driver;
    
    static public function observe()
    {
        return [
            'Post',
            'Tag'
        ];
    }
    
    static public function setDriverName($driverName, array $config = [])
    {
        $driverClass  = 'MyImouto\ExternalStorage\Driver\\' . $driverName;
        self::$driver = new $driverClass($config);
    }
    
    public function afterCreate()
    {
        // self::$driver->create
    }
    
    public function afterSave()
    {
    }
    
    public function afterUpdate()
    {
    }
    
    public function afterDestroy()
    {
    }
}
