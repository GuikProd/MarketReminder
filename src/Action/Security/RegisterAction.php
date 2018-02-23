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
use Symfony\Component\Routing\Annotation\Route;
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
 *
 * @Route(
 *     name="web_registration",
 *     path="/{_locale}/register",
 *     methods={"GET", "POST"},
 *     defaults={
 *         "_locale": "%locale%"
 *     },
 *     requirements={
 *         "_locale": "%accepted_locales%"
 *     }
 * )
 */
class RegisterAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var RegisterTypeHandlerInterface
     */
    private $registerTypeHandler;

    /**
     * RegisterAction constructor.
     *
     * @param FormFactoryInterface         $formFactory
     * @param UrlGeneratorInterface        $urlGenerator
     * @param EventDispatcherInterface     $eventDispatcher
     * @param RegisterTypeHandlerInterface $registerTypeHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        EventDispatcherInterface $eventDispatcher,
        RegisterTypeHandlerInterface $registerTypeHandler
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->registerTypeHandler = $registerTypeHandler;
    }

    /**
     * @param Request              $request
     * @param UserBuilderInterface $userBuilder
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
        UserBuilderInterface $userBuilder,
        SessionInterface $session,
        RegisterResponder $responder
    ) {
        $userBuilder->createUser();

        $registerType = $this->formFactory
                             ->create(RegisterType::class, $userBuilder->getUser())
                             ->handleRequest($request);

        if ($this->registerTypeHandler->handle($registerType, $userBuilder)) {
            $userCreatedEvent = new UserCreatedEvent($userBuilder->getUser());
            $this->eventDispatcher->dispatch(UserCreatedEvent::NAME, $userCreatedEvent);

            $session
                ->getFlashBag()
                ->add(
                    'success',
                    'Your account was created ! Please check your mail to validate it.'
                );

            return new RedirectResponse(
                $this->urlGenerator->generate('index')
            );
        }

        return $responder($registerType);
    }
}
