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

namespace App\Application\Helper\Image;

use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStoragePersisterHelperInterface;

/**
 * Class ImageUploaderHelper.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ImageUploaderHelper implements ImageUploaderHelperInterface
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $bucketName;

    /**
     * @var CloudStoragePersisterHelperInterface
     */
    private $cloudStoragePersister;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $filePath,
        string $bucketName,
        CloudStoragePersisterHelperInterface $cloudStoragePersister
    ) {
        $this->filePath = $filePath;
        $this->bucketName = $bucketName;
        $this->cloudStoragePersister = $cloudStoragePersister;
    }

    /**
     * {@inheritdoc}
     */
    public function generateFilename(\SplFileInfo $uploadedFile): ImageUploaderHelperInterface
    {
        $this->fileName = md5(str_rot13(uniqid())).'.'.$uploadedFile->guessExtension();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(\SplFileInfo $uploadedImage): ImageUploaderHelperInterface
    {
        $this->cloudStoragePersister
             ->persist(
                 $this->bucketName,
                 $uploadedImage->getPathname(),
                 ['name' => $this->fileName]
             );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}
