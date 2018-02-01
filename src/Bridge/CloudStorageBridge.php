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
use Symfony\Component\Config\FileLocator;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;

/**
 * Class CloudStorageBridge.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageBridge implements CloudStorageBridgeInterface
{
    /**
     * @var array
     */
    private $credentials;

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
    public function getServiceBuilder(): ServiceBuilder
    {
        return new ServiceBuilder([
            'keyFile' => $this->credentials
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function loadCredentialsFile(): CloudStorageBridgeInterface
    {
        $fileLocator = new FileLocator($this->bucketCredentialsFolder);

        $this->credentials = json_decode(
            file_get_contents(
                $fileLocator->locate('credentials.json')
            ), true
        );

        return $this;
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
