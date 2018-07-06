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

namespace App\Infra\GCP\CloudTranslation\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Google\Cloud\Translate\TranslateClient;

/**
 * Class CloudTranslationBridge.
 *
 * This class is responsible to create a new bridge
 * between the application and Google Cloud Platform Translation API.
 *
 * By default, the bridge isn't activated, the configuration can activate it
 * as soon as it need.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBridge implements CloudBridgeInterface, CloudTranslationBridgeInterface
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
    public function getTranslateClient(): TranslateClient
    {
        $this->credentialsLoader->loadJson($this->credentialsFileName, $this->credentialsFolder);

        return new TranslateClient([
            'keyFile' => $this->credentialsLoader->getCredentials()
        ]);
    }
}
