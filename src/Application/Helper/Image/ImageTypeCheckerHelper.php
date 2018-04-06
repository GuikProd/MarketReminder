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

use App\Application\Helper\Image\Interfaces\ImageTypeCheckerHelperInterface;

/**
 * Class ImageTypeCheckerHelper.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
abstract class ImageTypeCheckerHelper implements ImageTypeCheckerHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public static function checkType(\SplFileInfo $uploadedFile): bool
    {
        return in_array($uploadedFile->getMimeType(), self::ALLOWED_TYPES);
    }
}
