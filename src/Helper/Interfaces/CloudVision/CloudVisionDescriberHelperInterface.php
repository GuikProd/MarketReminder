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

namespace App\Helper\Interfaces\CloudVision;

use Google\Cloud\Vision\Image;
use Google\Cloud\Vision\Annotation;

/**
 * Interface CloudVisionDescriberHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudVisionDescriberHelperInterface
{
    /**
     * Allow to return the image attributes.
     *
     * @param Image $analysedImage    The image which's been analysed.
     *
     * @return Annotation             The information linked to the image.
     *
     * @see Annotation                Documentation purpose.
     */
    public function describe(Image $analysedImage): Annotation;
}
