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

namespace App\Domain\Builder\Interfaces;

use App\Domain\Models\Interfaces\ImageInterface;

/**
 * Interface ImageBuilderInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageBuilderInterface
{
    /**
     * @param string $alt
     * @param string $filename
     * @param string $publicUrl
     *
     * @return ImageBuilderInterface
     */
    public function build(string $alt, string $filename, string $publicUrl): ImageBuilderInterface;

    /**
     * @return ImageInterface
     */
    public function getImage(): ImageInterface;
}
