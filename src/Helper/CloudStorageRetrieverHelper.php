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

namespace App\Helper;

use Psr\Http\Message\StreamInterface;
use App\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Helper\Interfaces\CloudStorageRetrieverHelperInterface;

/**
 * Class CloudStorageRetrieverHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudStorageRetrieverHelper implements CloudStorageRetrieverHelperInterface
{
    /**
     * @var CloudStorageBridgeInterface
     */
    private $cloudStorageBridge;

    /**
     * CloudStorageRetrieverHelper constructor.
     *
     * @param CloudStorageBridgeInterface $cloudStorageBridge
     */
    public function __construct(CloudStorageBridgeInterface $cloudStorageBridge)
    {
        $this->cloudStorageBridge = $cloudStorageBridge;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve(string $bucketName, string $fileName, string $filePath): StreamInterface
    {
        return $this->cloudStorageBridge
                    ->getServiceBuilder()
                    ->storage()
                    ->bucket($bucketName)
                    ->object($fileName)
                    ->downloadToFile($filePath);
    }
}
