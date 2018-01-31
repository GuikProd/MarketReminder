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
 * Interface ImageRetrieverHelperInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageRetrieverHelperInterface
{
    /**
     * @param string $fileName    The name of the file to retrieve.
     *
     * @return string             The public url of the file.
     */
    public function getFileAsString(string $fileName): string;

    /**
     * @return string
     */
    public function getBucketName(): string;

    /**
     * @return string
     */
    public function getGoogleStoragePublicUrl(): string;
}
