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

use Symfony\Component\Config\FileLocator;
use App\Bridge\Interfaces\CloudBridgeInterface;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;

/**
 * Class CloudStorageBridge.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridge extends AbstractBridge implements CloudStorageBridgeInterface
{
    /**
     * @var string
     */
    private $bucketCredentialsFolder;

    /**
     * CloudStorageBridge constructor.
     *
     * @param string $bucketCredentialsFolder
     */
    public function __construct(string $bucketCredentialsFolder)
    {
        $this->bucketCredentialsFolder = $bucketCredentialsFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function loadCredentialsFile(): CloudBridgeInterface
    {
        $fileLocator = new FileLocator($this->bucketCredentialsFolder);

        $this->credentials = json_decode(
            file_get_contents(
                $fileLocator->locate('credentials.json')
            ), true
        );

        return $this;
    }
}
