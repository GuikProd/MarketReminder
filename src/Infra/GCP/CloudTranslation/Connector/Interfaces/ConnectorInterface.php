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

namespace App\Infra\GCP\CloudTranslation\Connector\Interfaces;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Interface ConnectorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ConnectorInterface
{
    /**
     * @return CacheItemPoolInterface
     */
    public function getAdapter(): CacheItemPoolInterface;
}
