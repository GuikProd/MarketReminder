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

namespace App\Domain\UseCase\ImageUpload\DTO;

use App\Domain\UseCase\ImageUpload\DTO\Interfaces\ImageUploadDTOInterface;

/**
 * Class ImageUploadDTO
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageUploadDTO implements ImageUploadDTOInterface
{
    /**
     * @var \SplFileInfo
     */
    public $image;

    /**
     * {@inheritdoc}
     */
    public function __construct(\SplFileInfo $image = null)
    {
        $this->image = $image;
    }
}
