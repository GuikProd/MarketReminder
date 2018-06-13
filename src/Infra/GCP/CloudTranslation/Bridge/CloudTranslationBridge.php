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
     * {@inheritdoc}
     */
    public function __construct(
        string $credentialsFileName,
        string $credentialsFolder
    ) {
        $this->credentialsFileName = $credentialsFileName;
        $this->credentialsFolder   = $credentialsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslateClient(): TranslateClient
    {
        return new TranslateClient([
            'keyFile' => json_decode(
                file_get_contents($this->credentialsFolder.'/'.$this->credentialsFileName),
                true
            )
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function closeConnexion(): void
    {
        $this->credentialsFileName = null;
        $this->credentialsFolder = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): array
    {
        return [
            'keyFile' => $this->credentialsFileName,
            'keyFilePath' => $this->credentialsFolder
        ];
    }
}
