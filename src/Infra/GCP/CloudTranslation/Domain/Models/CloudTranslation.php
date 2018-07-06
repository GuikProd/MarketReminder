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

namespace App\Infra\GCP\CloudTranslation\Domain\Models;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;

/**
 * Class CloudTranslation.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslation implements CloudTranslationInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var array
     */
    private $items = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, string $channel, array $items = [])
    {
        $this->name = $name;
        $this->channel = $channel;
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
