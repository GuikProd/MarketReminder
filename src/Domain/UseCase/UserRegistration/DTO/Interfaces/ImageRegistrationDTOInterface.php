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

namespace App\Domain\UseCase\UserRegistration\DTO\Interfaces;

/**
 * Interface ImageRegistrationDTOInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageRegistrationDTOInterface
{
    /**
     * ImageRegistrationDTOInterface constructor.
     *
     * @param string $alt
     * @param string $filename
     * @param string $publicUrl
     */
    public function __construct(string $alt, string $filename, string $publicUrl);
}
