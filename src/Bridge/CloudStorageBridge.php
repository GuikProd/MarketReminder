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

use Symfony\Component\Finder\Finder;
use Google\Cloud\Core\ServiceBuilder;
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
     * @var \SplFileInfo
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
        $finder = new Finder();

        $files = $finder->in($this->bucketCredentialsFolder)
                        ->files()
                        ->name('*.json');

        foreach ($files as $file) {
            if ($file->getFilename() === 'credentials.json') {
                $this->credentials = json_decode($file->getContents(), true);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function closeConnexion(): void
    {
        $this->credentials = null;
    }
}
