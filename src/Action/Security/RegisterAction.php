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

namespace App\Action\Security;

use App\Form\Type\RegisterType;
use App\Event\User\UserCreatedEvent;
use App\Responder\Security\RegisterResponder;
use Symfony\Component\HttpFoundation\Request;
use App\Builder\Interfaces\UserBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RegisterAction
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactoryInterface;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGeneratorInterface;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcherInterface;

    /**
     * @var RegisterTypeHandlerInterface
     */
    private $registerTypeHandlerInterface;

    /**
     * @param Request $request
     * @param UserBuilderInterface $userBuilderInterface
     * @param RegisterResponder $responder
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, UserBuilderInterface $userBuilderInterface, RegisterResponder $responder)
    {
        $userBuilderInterface->createUser();

        $registerType = $this->formFactoryInterface
                             ->create(RegisterType::class, $userBuilderInterface->getUser())
                             ->handleRequest($request);

        if ($this->registerTypeHandlerInterface->handle($registerType, $userBuilderInterface)) {

            $userCreatedEvent = new UserCreatedEvent($userBuilderInterface->getUser());
            $this->eventDispatcherInterface->dispatch(UserCreatedEvent::NAME, $userCreatedEvent);

            return new RedirectResponse(
                $this->urlGeneratorInterface->generate('index')
            );
        }

        return $responder($registerType);
    }
}
