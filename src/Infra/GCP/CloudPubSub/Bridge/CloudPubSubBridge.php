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

namespace App\Infra\GCP\CloudPubSub\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\CloudPubSub\Bridge\Interfaces\CloudPubSubBridgeInterface;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Google\Cloud\PubSub\PubSubClient;

/**
 * Class CloudPubSubBridge.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubBridge implements CloudPubSubBridgeInterface, CloudBridgeInterface
{
    /**
     * The default name of the credentials file.
     *
     * @var null|string
     */
    private $credentialsFileName = null;

    /**
     * The default folder where is located the credentials file.
     *
     * @var null|string
     */
    private $credentialsFolder = null;

    /**
     * @var LoaderInterface
     */
    private $credentialsLoader;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $credentialsFilename,
        string $credentialsFolder,
        LoaderInterface $loader
    ) {
        $this->credentialsFileName = $credentialsFilename;
        $this->credentialsFolder = $credentialsFolder;
        $this->credentialsLoader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getPubSubClient(): PubSubClient
    {
        $this->credentialsLoader->loadJson($this->credentialsFileName, $this->credentialsFolder);

        return new PubSubClient([
            'keyFile' => $this->credentialsLoader->getCredentials()
        ]);
    }
}
