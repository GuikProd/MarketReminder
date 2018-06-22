<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infra\GCP\CloudTranslation\Domain\Repository;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use Psr\Cache\CacheItemInterface;

/**
 * Class CloudTranslationRepository.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationRepository implements CloudTranslationRepositoryInterface
{
    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * @var CloudTranslationItemInterface
     */
    private $cacheEntry;

    /**
     * {@inheritdoc}
     */
    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries(string $filename): ?array
    {
        $cacheItem = $this->connector->getAdapter()->getItem($filename);

        return $cacheItem instanceof CacheItemInterface && $cacheItem->isHit()
            ? $cacheItem->get()->getItems()
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSingleEntry(string $filename, string $locale, string $key): ?CloudTranslationItemInterface
    {
        $cacheItem = $this->connector->getAdapter()->getItem($filename);

        if ($cacheItem->isHit() && \count($cacheItem->get()->getItems()) > 0) {
            foreach ($cacheItem->get()->getItems() as $k => $v) {
                if ($key === $v->getKey() && $locale === $v->getLocale()) {
                    $this->cacheEntry = $v;
                }
            }
        }

        return $this->cacheEntry ?? null;
    }
}
