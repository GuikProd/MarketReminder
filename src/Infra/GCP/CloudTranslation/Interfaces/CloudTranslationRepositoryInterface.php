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

namespace App\Infra\GCP\CloudTranslation\Interfaces;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;

/**
 * Interface CloudTranslationRepositoryInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationRepositoryInterface
{
    /**
     * CloudTranslationRepositoryInterface constructor.
     *
     * @param ConnectorInterface $connector
     * @param ConnectorInterface $backUpConnector
     */
    public function __construct(
        ConnectorInterface $connector,
        ConnectorInterface $backUpConnector
    );

    /**
     * Allow to retrieve an array of CloudTranslationItem using the default filename.
     *
     * @param string $filename  The name of the item to retrieve.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return array  The array which contains all the translations stored using the $filename.
     */
    public function getEntries(string $filename): ?array;

    /**
     * Allow to retrieve a single CloudTranslationItem using the default filename, is key and the locale.
     *
     * @param string $filename The name of the translation file.
     * @param string $locale   The locale used to return the translated content.
     * @param string $key      The key used during the storage process.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return CloudTranslationItemInterface|null
     */
    public function getSingleEntry(string $filename, string $locale, string $key): ?CloudTranslationItemInterface;
}
