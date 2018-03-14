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

namespace App\Domain\Event\Interfaces;

use App\Domain\Models\Interfaces\ImageInterface;

/**
 * Interface ImageEventInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageEventInterface
{
    /**
     * ImageEventInterface constructor.
     *
     * @param ImageInterface $image
     */
    public function __construct(ImageInterface $image);

    /**
     * @return ImageInterface
     */
    public function getImage(): ImageInterface;
}
