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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface RegisterTypeHandlerInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RegisterTypeHandlerInterface
{
    /**
     * RegisterTypeHandlerInterface constructor.
     *
     * @param ValidatorInterface        $validator
     * @param UserRepositoryInterface   $entityManager
     * @param EventDispatcherInterface  $eventDispatcher
     * @param EncoderFactoryInterface   $passwordEncoderFactory
     */
    public function __construct(
        ValidatorInterface $validator,
        UserRepositoryInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        EncoderFactoryInterface $passwordEncoderFactory
    );

    /**
     * @param FormInterface $registerForm  The RegisterType Form
     *
     * @return bool  If the handling process has succeed
     */
    public function handle(FormInterface $registerForm): bool;
}
