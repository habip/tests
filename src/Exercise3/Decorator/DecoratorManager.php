<?php

namespace Exercise3\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Exercise3\Integration\DataProvider;
use Exercise3\Integration\DataProviderInterface;

class DecoratorManager implements DataProviderInterface
{
    protected $dataProvider;
    protected $cache;
    protected $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(DataProvider $dataProvider, CacheItemPoolInterface $cache)
    {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function get(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->dataProvider->get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );
                
            $this->cache->save($cacheItem);

            return $result;
        } catch (Exception $e) {
            if ($this->logger) {
                $this->logger->critical('Error');
            }
        }

        return [];
    }

    protected function getCacheKey(array $input)
    {
        ksort($input);
        return json_encode($input);
    }
}
