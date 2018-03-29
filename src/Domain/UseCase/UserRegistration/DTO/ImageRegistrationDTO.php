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

namespace App\Domain\UseCase\UserRegistration\DTO;

use App\Domain\UseCase\UserRegistration\DTO\Interfaces\ImageRegistrationDTOInterface;

/**
 * Class ImageRegistrationDTO
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageRegistrationDTO implements ImageRegistrationDTOInterface
{
    /**
     * @var string
     */
    public $alt;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var string
     */
    public $publicUrl;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $alt,
        string $filename,
        string $publicUrl
    ) {
        $this->alt = $alt;
        $this->filename = $filename;
        $this->publicUrl = $publicUrl;
    }
}
