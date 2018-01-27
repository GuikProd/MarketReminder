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

namespace App\Builder\Interfaces;

use App\Models\Interfaces\ImageInterface;

/**
 * Interface ImageBuilderInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageBuilderInterface
{
    /**
     * @return ImageBuilderInterface
     */
    public function createImage(): ImageBuilderInterface;

    /**
     * @return ImageInterface
     */
    public function getImage(): ImageInterface;
}
