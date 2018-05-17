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

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Interface ResetPasswordTypeHandlerInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ResetPasswordTypeHandlerInterface
{
    /**
     * ResetPasswordTypeHandlerInterface constructor.
     *
     * @param EncoderFactoryInterface         $encoderFactory
     * @param EventDispatcherInterface        $eventDispatcher
     * @param PresenterInterface $resetPasswordPresenter
     * @param UserRepositoryInterface         $userRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EncoderFactoryInterface $encoderFactory,
        PresenterInterface $resetPasswordPresenter,
        UserRepositoryInterface $userRepository
    );

    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return bool
     */
    public function handle(
        FormInterface $form,
        UserInterface $user
    ): bool;
}
