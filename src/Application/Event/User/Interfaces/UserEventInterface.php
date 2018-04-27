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

namespace App\Application\Event\User\Interfaces;

use App\Domain\Models\Interfaces\UserInterface;
use App\UI\Presenter\User\Interfaces\UserEmailPresenterInterface;

/**
 * Interface UserEventInterface.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface UserEventInterface
{
    /**
     * UserCreatedEventInterface constructor.
     *
     * @param UserInterface               $user
     * @param UserEmailPresenterInterface $presenter
     */
    public function __construct(
        UserInterface $user,
        UserEmailPresenterInterface $presenter
    );

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;

    /**
     * @return UserEmailPresenterInterface
     */
    public function getEmailPresenter(): UserEmailPresenterInterface;
}