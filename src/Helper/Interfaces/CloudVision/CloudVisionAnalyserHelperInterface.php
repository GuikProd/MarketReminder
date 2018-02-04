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
 * Interface CloudVisionAnalyserHelperInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface CloudVisionAnalyserHelperInterface
{
    /**
     * Allow to send an image and define the analyse mode.
     *
     * @param string $imagePath      The path to the image.
     * @param string $analyseMode    The mode used by Vision to analyse the image.
     *
     * @return Image                 The object representation of the analyse.
     *
     * @see Image                    Documentation purpose.
     */
    public function analyse(string $imagePath, string $analyseMode): Image;
}
