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

use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use Behat\Behat\Context\Context;
use Symfony\Component\Config\FileLocator;

/**
 * Class CloudContext.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudContext implements Context
{
    /**
     * @var string
     */
    private $bucketName;

    /**
     * @var string
     */
    private $publicFilesFolder;

    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridgeInterface;

    /**
     * CloudContext constructor.
     *
     * @param string                      $bucketName
     * @param string                      $publicFilesFolder
     * @param CloudStorageBridgeInterface $cloudStorageBridgeInterface
     */
    public function __construct(
        string $bucketName,
        string $publicFilesFolder,
        CloudStorageBridgeInterface $cloudStorageBridgeInterface
    ) {
        $this->bucketName = $bucketName;
        $this->publicFilesFolder = $publicFilesFolder;
        $this->cloudStorageBridgeInterface = $cloudStorageBridgeInterface;
    }

    /**
     * Allow to find the file that's been already stored in the bucket.
     *
     * @BeforeScenario
     */
    public function findFiles()
    {
        $fileLocator = new FileLocator($this->publicFilesFolder);
        $files = $fileLocator->locate('*.png', null, false);

        foreach ($files as $file) {
            $this->cleanBucket($file);
        }
    }

    /**
     * Allow to clean the bucket.
     *
     * @param string $fileName the name of the file to delete
     */
    public function cleanBucket(string $fileName)
    {
        $this->cloudStorageBridgeInterface
             ->loadCredentialsFile()
             ->getServiceBuilder()
             ->storage()
             ->bucket($this->bucketName)
             ->object($fileName)
             ->delete();
    }
}
