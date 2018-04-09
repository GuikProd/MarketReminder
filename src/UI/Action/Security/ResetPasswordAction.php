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

namespace App\UI\Action\Security;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ResetPasswordActionInterface;
use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ResetPasswordAction.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordAction implements ResetPasswordActionInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        UserRepositoryInterface $userRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(
        Request $request,
        ResetPasswordResponderInterface $responder
    ): RedirectResponse {

        return $responder();
    }
}
