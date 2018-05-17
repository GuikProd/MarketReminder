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
use App\UI\Form\FormHandler\Interfaces\ResetPasswordTypeHandlerInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param EventDispatcherInterface          $eventDispatcher
     * @param FormFactoryInterface              $formFactory
     * @param PresenterInterface                $resetPasswordPresenter
     * @param ResetPasswordTypeHandlerInterface $resetPasswordTypeHandler
     * @param UserRepositoryInterface           $userRepository
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        PresenterInterface $resetPasswordPresenter,
        ResetPasswordTypeHandlerInterface $resetPasswordTypeHandler,
        UserRepositoryInterface $userRepository
    );

    /**
     * @param Request                          $request
     * @param ResetPasswordResponderInterface  $responder
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        ResetPasswordResponderInterface $responder
    ): Response;
}
