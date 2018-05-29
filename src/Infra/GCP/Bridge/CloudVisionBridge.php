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

namespace App\Infra\GCP\Bridge;

use App\Infra\GCP\Bridge\Interfaces\CloudBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Vision\VisionClient;

/**
 * Class CloudVisionBridge.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudVisionBridge implements CloudBridgeInterface, CloudVisionBridgeInterface
{
    /**
     * @var string
     */
    private $visionCredentialsFileName;

    /**
     * @var string
     */
    private $visionCredentialsFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $visionCredentialsFileName,
        string $visionCredentialsFolder
    ) {
        $this->visionCredentialsFileName = $visionCredentialsFileName;
        $this->visionCredentialsFolder = $visionCredentialsFolder;
    }

    /**
     * {@inheritdoc}
     *
     * @throws GoogleException
     */
    public function getVisionClient(): VisionClient
    {
        return new VisionClient([
            'keyFile' => json_decode(file_get_contents($this->visionCredentialsFolder.'/'.$this->visionCredentialsFileName), true)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function closeConnexion(): void
    {
        $this->visionCredentialsFileName = null;
        $this->visionCredentialsFolder = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): array
    {
        return [
            'keyFileName' => $this->visionCredentialsFileName,
            'keyFilePath' => $this->visionCredentialsFolder
        ];
    }
}
