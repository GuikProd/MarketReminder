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

namespace App\Domain\Prototype;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Prototype\Interfaces\UserPrototypeInterface;

/**
 * Class UserPrototype.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserPrototype implements UserPrototypeInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function createFromUser(UserInterface $existingUser): UserPrototypeInterface
    {
        $this->user = clone $existingUser;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrototype(): UserInterface
    {
        return $this->user;
    }
}
