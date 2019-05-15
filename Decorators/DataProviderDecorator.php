<?php

namespace src\Decorators;

use Psr\Cache\CacheItemPoolInterface;
use src\Integration\DataProviderInterface;

class DataProviderDecorator implements DataProviderInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var DataProviderInterface
     */
    private $provider;

    /**
     * @param DataProviderInterface $provider
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(DataProviderInterface $provider, CacheItemPoolInterface $cache)
    {
        $this->provider = $provider;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function get(array $input): array
    {
        $cacheKey = $this->getCacheKey($input);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $result = $this->provider->get($input);

        $cacheItem
            ->set($result)
            ->expiresAt(
                (new \DateTime)->modify('+1 day')
            );

        return $result;
    }

    /**
     * Returns cache key by input data
     *
     * @param array $input
     *
     * @return string
     */
    private function getCacheKey(array $input): string
    {
        return json_encode($input);
    }
}