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

namespace App\Application\Helper\Image\Interfaces;

use App\Infra\GCP\CloudStorage\Interfaces\CloudStorageRetrieverHelperInterface;

/**
 * Interface ImageRetrieverHelperInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageRetrieverHelperInterface
{
    /**
     * ImageRetrieverHelperInterface constructor.
     *
     * @param string                                $bucketName
     * @param CloudStorageRetrieverHelperInterface  $cloudRetrieverHelper
     * @param string                                $googleStoragePublicUrl
     */
    public function __construct(
        string $bucketName,
        CloudStorageRetrieverHelperInterface $cloudRetrieverHelper,
        string $googleStoragePublicUrl
    );

    /**
     * Return the public url + the filename.
     *
     * @param string $fileName    The name of the file to retrieve.
     *
     * @return string             The public url of the file.
     */
    public function getFileAsString(string $fileName): string;

    /**
     * Return the bucket name.
     *
     * @return string
     */
    public function getBucketName(): string;

    /**
     * Return the public url of the bucket, the filename MUST be append.
     *
     * @return string
     */
    public function getGoogleStoragePublicUrl(): string;
}
