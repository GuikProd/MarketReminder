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

namespace App\Application\Helper\Image;

use App\Application\Helper\CloudStorage\Interfaces\CloudStorageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;

/**
 * Class ImageRetrieverHelper
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageRetrieverHelper implements ImageRetrieverHelperInterface
{
    /**
     * @var string
     */
    private $bucketName;

    /**
     * @var CloudStorageRetrieverHelperInterface
     */
    private $cloudRetrieverHelper;

    /**
     * @var string
     */
    private $googleStoragePublicUrl;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $bucketName,
        CloudStorageRetrieverHelperInterface $cloudRetrieverHelper,
        string $googleStoragePublicUrl
    ) {
        $this->bucketName = $bucketName;
        $this->cloudRetrieverHelper = $cloudRetrieverHelper;
        $this->googleStoragePublicUrl = $googleStoragePublicUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileAsString(string $fileName): string
    {
        return $this->cloudRetrieverHelper->checkFileExistence($this->bucketName, $fileName)
               ? htmlspecialchars($this->cloudRetrieverHelper->retrieveAsString($this->bucketName, $fileName))
               : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getBucketName(): string
    {
        return $this->bucketName;
    }

    /**
     * {@inheritdoc}
     */
    public function getGoogleStoragePublicUrl(): string
    {
        return $this->googleStoragePublicUrl;
    }
}
