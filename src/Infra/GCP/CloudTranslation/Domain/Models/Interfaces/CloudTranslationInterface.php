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

namespace App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces;

/**
 * Interface CloudTranslationInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationInterface
{
    /**
     * CloudTranslationInterface constructor.
     *
     * @param string $name    The name of the file which contain the translation.
     * @param string $channel The channel used by the translation.
     * @param array  $items   The different items contained by the file.
     */
    public function __construct(string $name, string $channel, array $items = []);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getChannel(): string;

    /**
     * @return array
     */
    public function getItems(): array;
}
