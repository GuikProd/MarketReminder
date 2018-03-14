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

namespace App\Domain\Event\Image;

use App\Domain\Event\Interfaces\ImageEventInterface;
use App\Domain\Models\Interfaces\ImageInterface;

/**
 * Class ImageUpdatedEvent
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUpdatedEvent implements ImageEventInterface
{
    const NAME = 'image.updated';

    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * ImageUpdatedEvent constructor.
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
