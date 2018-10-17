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

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Interface AskResetPasswordTypeHandlerInterface
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface AskResetPasswordTypeHandlerInterface
{
    /**
     * AskResetPasswordTypeHandlerInterface constructor.
     *
     * @param MessageBusInterface $messageBus
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        MessageBusInterface $messageBus,
        UserRepositoryInterface $userRepository
    );

    /**
     * @param FormInterface $askResetPasswordType
     *
     * @return bool
     */
    public function handle(FormInterface $askResetPasswordType): bool;
}
