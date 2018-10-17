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

namespace App\UI\Form\FormHandler\Security\Interfaces;

use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Interface ResetPasswordTypeHandlerInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ResetPasswordTypeHandlerInterface
{
    /**
     * ResetPasswordTypeHandlerInterface constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param MessageBusInterface $messageBus
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        MessageBusInterface $messageBus,
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
