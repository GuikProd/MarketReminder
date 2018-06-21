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

use App\Infra\GCP\CloudTranslation\Connector\BackUp\Interfaces\BackUpConnectorInterface;
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
     * @var BackUpConnectorInterface
     */
    private $backUpConnector;

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
    public function __construct(
        ConnectorInterface $connector,
        BackUpConnectorInterface $backUpConnector
    ) {
        $this->connector = $connector;
        $this->backUpConnector = $backUpConnector;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries(string $filename): ?array
    {
        $cacheItem = $this->connector->getAdapter()->getItem($filename);

        $toReturn = $cacheItem instanceof CacheItemInterface && $cacheItem->isHit()
            ? $cacheItem->get()->getItems()
            : null;

        if (\is_null($toReturn)) {
            $this->backUpConnector->activate(true);

            $backUpItem = $this->backUpConnector->getBackUpAdapter()->getItem($filename);

            $backUpItem instanceof CacheItemInterface && $backUpItem->isHit()
                ? $toReturn = $backUpItem->get()->getItems()
                : null;
        }

        return $toReturn;
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
                    return $this->cacheEntry = $v;
                }
            }
        }

        $this->backUpConnector->activate(true);

        $backUpItem = $this->backUpConnector->getBackUpAdapter()->getItem($filename);

        if ($backUpItem->isHit() && \count($cacheItem->get()->getItems()) > 0) {
            foreach ($backUpItem->get()->getItems() as $item => $value) {
                if ($value->getKey() === $key && $value->getLocale() === $locale) {
                    $this->cacheEntry = $value;
                }
            }
        }

        return $this->cacheEntry ?? null;
    }
}
