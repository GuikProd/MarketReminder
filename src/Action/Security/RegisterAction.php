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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RegisterAction.
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
     * RegisterAction constructor.
     *
     * @param FormFactoryInterface         $formFactoryInterface
     * @param UrlGeneratorInterface        $urlGeneratorInterface
     * @param EventDispatcherInterface     $eventDispatcherInterface
     * @param RegisterTypeHandlerInterface $registerTypeHandlerInterface
     */
    public function __construct(
        FormFactoryInterface $formFactoryInterface,
        UrlGeneratorInterface $urlGeneratorInterface,
        EventDispatcherInterface $eventDispatcherInterface,
        RegisterTypeHandlerInterface $registerTypeHandlerInterface
    ) {
        $this->formFactoryInterface = $formFactoryInterface;
        $this->urlGeneratorInterface = $urlGeneratorInterface;
        $this->eventDispatcherInterface = $eventDispatcherInterface;
        $this->registerTypeHandlerInterface = $registerTypeHandlerInterface;
    }

    /**
     * @param Request              $request
     * @param UserBuilderInterface $userBuilderInterface
     * @param SessionInterface     $session
     * @param RegisterResponder    $responder
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        UserBuilderInterface $userBuilderInterface,
        SessionInterface $session,
        RegisterResponder $responder
    ) {
        $userBuilderInterface->createUser();

        $registerType = $this->formFactoryInterface
                             ->create(RegisterType::class, $userBuilderInterface->getUser())
                             ->handleRequest($request);

        if ($this->registerTypeHandlerInterface->handle($registerType, $userBuilderInterface)) {
            $userCreatedEvent = new UserCreatedEvent($userBuilderInterface->getUser());
            $this->eventDispatcherInterface->dispatch(UserCreatedEvent::NAME, $userCreatedEvent);

            $session
                ->getFlashBag()
                ->add(
                    'success',
                    'Your account was created ! Please check your mail to validate it.'
                );

            return new RedirectResponse(
                $this->urlGeneratorInterface->generate('index')
            );
        }

        return $responder($registerType);
    }
}
