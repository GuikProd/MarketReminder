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
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Class CloudVisionBridge.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudVisionBridge extends AbstractBridge implements CloudVisionBridgeInterface
{
    /**
     * @var string
     */
    private $visionCredentialsFolder;

    /**
     * CloudVisionBridge constructor.
     *
     * @param string $visionCredentialsFolder
     */
    public function __construct(string $visionCredentialsFolder)
    {
        $this->visionCredentialsFolder = $visionCredentialsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function loadCredentialsFile(): CloudBridgeInterface
    {
        $fileLocator = new FileLocator($this->visionCredentialsFolder);

        $this->credentials = json_decode(
            file_get_contents(
                $fileLocator->locate('credentials.json')
            ), true
        );

        return $this;
    }
}
