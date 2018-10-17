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

namespace App\Domain\Factory;

use App\Domain\Factory\Interfaces\UserFactoryInterface;
use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;

/**
 * Class UserFactory
 *
 * @package App\Domain\Factory
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserFactory implements UserFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFromUI(
        string $username,
        string $email,
        string $password,
        ImageInterface $image = null
    ): UserInterface {
        return new User($email, $username, $password, $image);
    }
}
