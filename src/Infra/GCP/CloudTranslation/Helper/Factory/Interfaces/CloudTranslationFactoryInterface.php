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

namespace App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;

/**
 * Interface CloudTranslationFactoryInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationFactoryInterface
{
    /**
     * @param string $message
     * @param string $channel
     * @param array $items
     * @return CloudTranslationInterface
     */
    public function buildCloudTranslation(
        string $message,
        string $channel,
        array $items = []
    ): CloudTranslationInterface;

    /**
     * @param string $locale
     * @param string $channel
     * @param string $key
     * @param string $value
     */
    public function buildCloudTranslationItem(
        string $locale,
        string $channel,
        string $key,
        string $value
    ): void;

    /**
     * @return array
     */
    public function getCloudTranslationItem(): array;
}
