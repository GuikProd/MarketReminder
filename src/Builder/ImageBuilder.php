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

namespace App\Builder;

use App\Interactor\ImageInteractor;
use App\Models\Interfaces\ImageInterface;
use App\Builder\Interfaces\ImageBuilderInterface;

/**
 * Class ImageBuilder.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
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
    public function createImage(): ImageBuilderInterface
    {
        $this->image = new ImageInteractor();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getImage(): ImageInterface
    {
        return $this->image;
    }
}
