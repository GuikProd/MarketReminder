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

namespace App\Helper\Interfaces;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface ImageUploaderHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageUploaderHelperInterface
{
    /**
     * @param \SplFileInfo $uploadedFile
     */
    public function checkExtension(\SplFileInfo $uploadedFile): void;

    /**
     * Store the file locally using UploadedFile::move().
     *
     * @param \SplFileInfo $uploadedFile       The actual file.
     *
     * @return ImageUploaderHelperInterface    Return itself for fluent call.
     *
     * @see UploadedFile
     */
    public function store(\SplFileInfo $uploadedFile): self;

    /**
     * @return ImageUploaderHelperInterface
     */
    public function upload(): self;

    /**
     * Return the name of the file when stored.
     *
     * @return string
     */
    public function getFileName(): string;

    /**
     * Return the local path of the file.
     *
     * @return string
     */
    public function getFilePath(): string;

    /**
     * @return string
     */
    public function getFileExtension(): string;

    /**
     * @return bool
     */
    public function getAllowedToSave(): bool;
}
