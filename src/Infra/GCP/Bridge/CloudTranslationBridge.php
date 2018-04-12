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

namespace App\Infra\GCP\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Google\Cloud\Translate\TranslateClient;

/**
 * Class CloudTranslationBridge.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationBridge implements CloudBridgeInterface, CloudTranslationBridgeInterface
{
    /**
     * @var string
     */
    private $credentialsFileName;

    /**
     * @var string
     */
    private $credentialsFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $translationCredentialsFileName,
        string $translationCredentialsFolder
    ) {
        $this->credentialsFileName = $translationCredentialsFileName;
        $this->credentialsFolder = $translationCredentialsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslateClient(): TranslateClient
    {
        return new TranslateClient([
            'keyFile' => json_decode(file_get_contents($this->credentialsFolder.'/'.$this->credentialsFileName), true)
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
