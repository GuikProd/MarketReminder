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

namespace App\Infra\GCP\CloudTranslation\Helper\Interfaces;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;

/**
 * Interface CloudTranslationWriterInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationWriterInterface
{
    /**
     * CloudTranslationWriterInterface constructor.
     *
     * @param ConnectorInterface $connector
     * @param CloudTranslationFactoryInterface $cloudTranslationFactory
     * @param CloudTranslationValidatorInterface $cloudTranslationValidator
     */
    public function __construct(
        ConnectorInterface $connector,
        CloudTranslationFactoryInterface $cloudTranslationFactory,
        CloudTranslationValidatorInterface $cloudTranslationValidator
    );

    /**
     * Allow to store a new item in the Cache, a CloudTranslationItem is created and stored.
     *
     * For security purpose, the tag is generated using a @see Uuid::uuid4().
     *
     * For reading purpose, the item is tagged with the Uuid tag.
     *
     * @internal A recursive call is done in order to invalidate the item if is
     *           content is out of date, this way, the cache is cleaned.
     *
     * @param string $locale   The locale used by the translation.
     * @param string $channel  The channel used by the item (used for retrieving process).
     * @param string $fileName The name of the file to cache (used as a key inside the cache along with the tag).
     * @param array  $values   The array of values to cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException @see TagAwareAdapter::getItem()
     *
     * @return bool If the write process has succeed or if the cache item is fresh.
     */
    public function write(string $locale, string $channel, string $fileName, array $values): bool;
}
