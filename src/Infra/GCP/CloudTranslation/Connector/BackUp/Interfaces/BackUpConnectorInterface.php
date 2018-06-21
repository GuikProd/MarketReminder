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

namespace App\Infra\GCP\CloudTranslation\Connector\BackUp\Interfaces;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Interface BackUpConnectorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface BackUpConnectorInterface
{
    /**
     * @return CacheItemPoolInterface
     */
    public function getBackUpAdapter(): CacheItemPoolInterface;

    /**
     * @param bool $isActivated
     */
    public function activate(bool $isActivated): void;
}
