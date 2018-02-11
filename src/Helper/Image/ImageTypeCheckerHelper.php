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

namespace App\Helper\Image;

use App\Helper\Interfaces\Image\ImageTypeCheckerHelperInterface;

/**
 * Class ImageTypeCheckerHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class ImageTypeCheckerHelper implements ImageTypeCheckerHelperInterface
{
    const ALLOWED_TYPES = ['image/png', 'image/jpeg'];

    /**
     * {@inheritdoc}
     */
    public static function checkType(\SplFileInfo $uploadedFile): bool
    {
        return in_array($uploadedFile->getMimeType(), self::ALLOWED_TYPES);
    }
}