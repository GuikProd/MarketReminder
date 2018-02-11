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

use App\Interactor\UserInteractor;
use App\Event\User\UserValidatedEvent;
use App\Helper\User\UserValidatorHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Responder\Security\ValidationTokenResponder;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ValidationTokenAction;
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ValidationTokenAction
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * ValidationTokenAction constructor.
     *
     * @param SessionInterface         $session
     * @param TranslatorInterface      $translator
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->session = $session;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @param ValidationTokenResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function __invoke(
        Request $request,
        ValidationTokenResponder $responder
    ) {
        $user = $this->entityManager
                     ->getRepository(UserInteractor::class)
                     ->getUserbyToken($request->attributes->get('token'));

        if (!$user) {
            $this->session
                 ->getFlashBag()
                 ->add(
                     'failure',
                     $this->translator
                          ->trans('security.validation_failure.notFound_token', [], 'messages')
                 );

            return $responder();
        } elseif ($user->getValidated()) {
            $this->session
                 ->getFlashBag()
                 ->add(
                     'failure',
                     $this->translator
                          ->trans('security.validation_failure.already_validated', [], 'messages')
                 );

            return $responder();
        }

        UserValidatorHelper::validate($user);

        $event = new UserValidatedEvent($user);
        $this->eventDispatcher->dispatch(UserValidatedEvent::NAME, $event);

        $this->entityManager->flush();

        $this->session
             ->getFlashBag()
             ->add(
                 'success',
                 $this->translator
                      ->trans('security.validation_success', [], 'messages')
             );

        return $responder();
    }
}
