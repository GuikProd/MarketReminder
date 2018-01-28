<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Bridge\Interfaces;

use Google\Cloud\Core\ServiceBuilder;

/**
 * Interface CloudStorageBridgeInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudStorageBridgeInterface
{
    /**
     * @return ServiceBuilder
     */
    public function getServiceBuilder(): ServiceBuilder;

    /**
     * @return CloudStorageBridgeInterface
     */
    public function loadCredentialsFile(): CloudStorageBridgeInterface;
}
