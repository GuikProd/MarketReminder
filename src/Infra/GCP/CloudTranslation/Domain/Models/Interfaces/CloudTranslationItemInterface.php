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
 * Interface CloudTranslationItemInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationItemInterface
{
    /**
     * CloudTranslationItemInterface constructor.
     *
     * @param array $options
     */
    public function __construct(array $options);

    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @return string
     */
    public function getChannel(): string;

    /**
     * @return string
     */
    public function getTag(): string;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return string
     */
    public function getValue(): string;
}
