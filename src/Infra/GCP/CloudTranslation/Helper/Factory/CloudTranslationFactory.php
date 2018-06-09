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

namespace App\Infra\GCP\CloudTranslation\Helper\Factory;

use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslation;
use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;

/**
 * Class CloudTranslationFactory.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationFactory implements CloudTranslationFactoryInterface
{
    /**
     * @var CloudTranslationItemInterface[]
     */
    private $cloudTranslationItems = [];

    /**
     * {@inheritdoc}
     */
    public function buildCloudTranslation(
        string $message,
        string $channel,
        array $items = []
    ): CloudTranslationInterface {

        return new CloudTranslation($message, $channel, $items);
    }

    /**
     * {@inheritdoc}
     */
    public function buildCloudTranslationItem(
        string $locale,
        string $channel,
        string $key,
        string $value
    ): void {

        $this->cloudTranslationItems[] =  new CloudTranslationItem([
            '_locale' => $locale,
            'channel' => $channel,
            'tag' => md5(uniqid(str_rot13($locale))),
            'key' => $key,
            'value' => $value
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCloudTranslationItem(): array
    {
        return $this->cloudTranslationItems;
    }
}
