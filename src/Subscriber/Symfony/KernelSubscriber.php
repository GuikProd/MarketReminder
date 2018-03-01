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

namespace App\Subscriber\Symfony;

use App\Interactor\UserInteractor;
use App\Subscriber\Interfaces\KernelSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class KernelSubscriber.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class KernelSubscriber implements
      EventSubscriberInterface,
      KernelSubscriberInterface
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
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * KernelSubscriber constructor.
     *
     * @param SessionInterface        $session
     * @param TranslatorInterface     $translator
     * @param UrlGeneratorInterface   $urlGenerator
     * @param EntityManagerInterface  $entityManager
     */
    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ) {
        $this->session = $session;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onUserValidation'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onUserValidation(GetResponseEvent $event): void
    {
        if ($event->getRequest()->attributes->get('_route') !== 'web_validation') {
            return;
        }

        $user = $this->entityManager
                     ->getRepository(UserInteractor::class)
                     ->getUserByToken($event->getRequest()->attributes->get('token'));

        if (!$user || $user->getValidated()) {
            $this->session
                 ->getFlashBag()
                 ->add(
                     'failure',
                     $this->translator
                          ->trans('security.validation_failure.notFound_token', [], 'messages')
                 );
            $event->setResponse(
                new RedirectResponse(
                    $this->urlGenerator->generate('index')
                )
            );
        }

        $event->getRequest()->attributes->set('user', $user);
    }

    /**
     * {@inheritdoc}
     */
    public function onUserAskResetPassword(GetResponseEvent $event): void
    {
        if ($event->getRequest()->attributes->get('_route') !== 'web_ask_reset_password') {
            return;
        }


    }
}
