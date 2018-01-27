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

namespace App\Interactor;

use App\Models\Image;
use App\Models\Interfaces\UserInterface;

/**
 * Class ImageInteractor
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ImageInteractor extends Image
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }
}
