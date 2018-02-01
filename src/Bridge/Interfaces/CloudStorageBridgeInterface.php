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
     * Allow to return a ServiceBuilder configured with the credentials.
     *
     * @return ServiceBuilder
     */
    public function getServiceBuilder(): ServiceBuilder;

    /**
     * Allow to load the credentials linked to this bridge.
     *
     * @return CloudStorageBridgeInterface
     */
    public function loadCredentialsFile(): CloudStorageBridgeInterface;

    /**
     * Allow to get the credentials.
     *
     * @return null|array
     */
    public function getCredentials():? array;

    /**
     * Allow to close the connexion via ths service account.
     */
    public function closeConnexion(): void;
}
