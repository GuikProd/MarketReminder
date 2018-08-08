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

namespace App\Infra\GCP\CloudPubSub\Message\Interfaces;

/**
 * Interface MessageInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface MessageInterface
{
    /**
     * @return string
     */
    public function getTopic(): string;

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @return array
     */
    public function getData(): array;
}
