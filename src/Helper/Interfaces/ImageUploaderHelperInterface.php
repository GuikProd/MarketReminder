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
     * @param \SplFileInfo $uploadedFile
     *
     * @return ImageUploaderHelperInterface
     */
    public function store(\SplFileInfo $uploadedFile): self;

    /**
     * @return ImageUploaderHelperInterface
     */
    public function upload(): self;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @return string
     */
    public function getFileExtension(): string;

    /**
     * @return bool
     */
    public function getAllowedToSave(): bool;
}
