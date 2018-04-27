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

namespace App\UI\Presenter\User\Interfaces;

/**
 * Interface UserEmailPresenterInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserEmailPresenterInterface
{
    /**
     * @return array
     */
    public function getEmail(): array;

    /**
     * @return array
     */
    public function getUser(): array;
}
