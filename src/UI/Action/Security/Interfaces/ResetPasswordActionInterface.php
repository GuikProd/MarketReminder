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

namespace App\UI\Action\Security\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface ResetPasswordActionInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ResetPasswordActionInterface
{
    /**
     * ResetPasswordActionInterface constructor.
     *
     * @param EventDispatcherInterface  $eventDispatcher
     * @param FormFactoryInterface      $formFactory
     * @param UserRepositoryInterface   $userRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        UserRepositoryInterface $userRepository
    );

    /**
     * @param Request                          $request
     * @param ResetPasswordResponderInterface  $responder
     *
     * @return RedirectResponse
     */
    public function __invoke(Request $request, ResetPasswordResponderInterface $responder): RedirectResponse;
}
