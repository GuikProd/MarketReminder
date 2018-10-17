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

namespace App\UI\Action\Security;

use App\Application\Messenger\Message\SessionMessage;
use App\Application\Messenger\Message\UserMessage;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ValidationTokenActionInterface;
use App\UI\Responder\Security\Interfaces\ValidationTokenResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ValidationTokenAction.
 *
 * @package App\UI\Action\Security
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @Route(
 *     name="web_validation",
 *     path="/validation/{token}",
 *     methods={"GET"}
 * )
 */
final class ValidationTokenAction implements ValidationTokenActionInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        MessageBusInterface $messageBus,
        UserRepositoryInterface $userRepository
    ) {
        $this->messageBus = $messageBus;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(
        Request $request,
        ValidationTokenResponderInterface $responder
    ): RedirectResponse {

        if (\is_null($user = $this->userRepository->getUserByToken($request->attributes->get('token')))) {

            $this->messageBus->dispatch(new SessionMessage(
                'failure',
                'security.validation_failure.notFound_token'
            ));

            return $responder();
        }

        $user->validate();

        $this->userRepository->flush();

        $this->messageBus->dispatch(new UserMessage([
            '_topic' => 'reset_password',
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail()
        ]));

        $this->messageBus->dispatch(new SessionMessage(
            'success',
            'security.validation_success'
        ));

        return $responder();
    }
}
