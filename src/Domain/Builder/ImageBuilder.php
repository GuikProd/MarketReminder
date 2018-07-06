<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Models\Image;
use App\Domain\Models\Interfaces\ImageInterface;

/**
 * Class ImageBuilder
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ImageBuilder implements ImageBuilderInterface
{
    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * {@inheritdoc}
     */
    public function build(string $alt, string $filename, string $publicUrl): ImageBuilderInterface
    {
        $this->image = new Image($alt, $filename, $publicUrl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getImage():? ImageInterface
    {
        return $this->image;
    }
}
