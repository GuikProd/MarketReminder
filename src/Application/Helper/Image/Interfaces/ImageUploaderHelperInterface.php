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

namespace App\Application\Helper\Image\Interfaces;

use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface ImageUploaderHelperInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ImageUploaderHelperInterface
{
    /**
     * ImageUploaderHelperInterface constructor.
     *
     * @param string                                $filePath
     * @param string                                $bucketName
     * @param CloudStorageWriterHelperInterface  $cloudStoragePersister
     */
    public function __construct(
        string $filePath,
        string $bucketName,
        CloudStorageWriterHelperInterface $cloudStoragePersister
    );

    /**
     * Allow to generate a random filename used when the file is persisted.
     *
     * @param \SplFileInfo $uploadedFile       The actual file.
     *
     * @return ImageUploaderHelperInterface    Return itself for fluent call.
     *
     * @see UploadedFile
     */
    public function generateFilename(\SplFileInfo $uploadedFile): self;

    /**
     * @param \SplFileInfo $uploadedImage
     *
     * @return ImageUploaderHelperInterface
     */
    public function upload(\SplFileInfo $uploadedImage): self;

    /**
     * Return the name of the file when stored.
     *
     * @return string
     */
    public function getFileName(): string;
}
