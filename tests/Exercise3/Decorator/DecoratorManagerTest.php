<?php

use Exercise3\Integration\DataProvider;
use Exercise3\Decorator\DecoratorManager;

class DecoratorManagerTest extends PHPUnit_Framework_TestCase
{
    public function testDecoratorManager()
    {
        $dataProvider = new DataProvider('localhost', 'user', 'password');
        $cacheItemPool = $this->getMockBuilder('Psr\Cache\CacheItemPoolInterface')->getMock();
        $decorator = new DecoratorManager($dataProvider, $cacheItemPool);
    }
}
