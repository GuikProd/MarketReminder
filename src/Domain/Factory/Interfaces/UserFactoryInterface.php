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

namespace App\Domain\Factory\Interfaces;

use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;

/**
 * Interface UserFactoryInterface.
 *
 * @package App\Domain\Factory\Interfaces
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserFactoryInterface
{
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param ImageInterface|null $image
     *
     * @return UserInterface
     *
     * @throws \Exception @see UuidInterface
     */
    public function createFromUI(
        string $username,
        string $email,
        string $password,
        ImageInterface $image = null
    ): UserInterface;
}
