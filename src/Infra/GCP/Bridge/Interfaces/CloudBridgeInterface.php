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

namespace App\Infra\GCP\Bridge\Interfaces;

/**
 * Interface CloudBridgeInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudBridgeInterface
{
    /**
     * @return array
     */
    public function getCredentials(): array;

    /**
     * Allow to close the connexion via ths service account.
     */
    public function closeConnexion(): void;
}
