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

namespace App\Event\Image;

use App\Models\Interfaces\ImageInterface;
use App\Event\Interfaces\ImageEventInterface;

/**
 * Class ImageAddedEvent
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageAddedEvent implements ImageEventInterface
{
    const NAME = 'image.added';

    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * ImageAddedEvent constructor.
     *
     * @param ImageInterface $image
     */
    public function __construct(ImageInterface $image)
    {
        $this->image = $image;
    }

    /**
     * {@inheritdoc}
     */
    public function getImage(): ImageInterface
    {
        return $this->image;
    }
}
