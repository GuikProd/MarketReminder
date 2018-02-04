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

namespace App\Bridge;

use Google\Cloud\Core\ServiceBuilder;
use App\Bridge\Interfaces\CloudBridgeInterface;

/**
 * Class AbstractBridge.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class AbstractBridge implements CloudBridgeInterface
{
    /**
     * @var array
     */
    protected $credentials;

    /**
     * {@inheritdoc}
     */
    public function getServiceBuilder(): ServiceBuilder
    {
        return new ServiceBuilder([
            'keyFile' => $this->credentials
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials():? array
    {
        return $this->credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function closeConnexion(): void
    {
        $this->credentials = null;
    }
}
