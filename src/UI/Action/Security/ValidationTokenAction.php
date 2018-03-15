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

use App\Domain\Event\User\UserValidatedEvent;
use App\Responder\Security\ValidationTokenResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ValidationTokenAction;
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * @Route(
 *     name="web_validation",
 *     path="/{_locale}/validation/{token}",
 *     methods={"GET"},
 *     defaults={
 *         "_locale": "%locale%"
 *     },
 *     requirements={
 *         "_locale": "%accepted_locales%",
 *         "token": "^[a-zA-Z0-9]+"
 *     }
 * )
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
     * @param Request                  $request
     * @param ValidationTokenResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function __invoke(
        Request $request,
        ValidationTokenResponder $responder
    ) {
        $request->attributes->get('user')->validate();

        $event = new UserValidatedEvent($request->attributes->get('user'));
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
