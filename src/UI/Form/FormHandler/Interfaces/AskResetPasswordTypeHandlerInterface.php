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

namespace App\UI\Form\FormHandler\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Presenter\User\Interfaces\UserEmailPresenterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Interface AskResetPasswordTypeHandlerInterface
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface AskResetPasswordTypeHandlerInterface
{
    /**
     * AskResetPasswordTypeHandlerInterface constructor.
     *
     * @param EventDispatcherInterface    $eventDispatcher
     * @param SessionInterface            $session
     * @param UserEmailPresenterInterface $presenter
     * @param UserRepositoryInterface     $userRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session,
        UserEmailPresenterInterface $presenter,
        UserRepositoryInterface $userRepository
    );

    /**
     * @param FormInterface $askResetPasswordType
     *
     * @return bool
     */
    public function handle(FormInterface $askResetPasswordType): bool;
}
