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

namespace App\Helper\Interfaces\Image;

/**
 * Interface ImageTypeCheckerHelperInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageTypeCheckerHelperInterface
{
    /**
     * Allow to know if a file has the correct extension and can be managed.
     *
     * @param \SplFileInfo $uploadedFile
     *
     * @return bool    Whether or not the file has a correct extension.
     */
    public static function checkType(\SplFileInfo $uploadedFile): bool;
}
