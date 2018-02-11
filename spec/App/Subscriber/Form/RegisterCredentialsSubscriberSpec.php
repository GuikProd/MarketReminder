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

namespace spec\App\Subscriber\Form;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Subscriber\Interfaces\RegisterCredentialsSubscriberInterface;

/**
 * Class RegisterCredentialsSubscriberSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterCredentialsSubscriberSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|TranslatorInterface    $translator
     * @param EntityManagerInterface|\PhpSpec\Wrapper\Collaborator $entityManager
     */
    public function it_implements(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager
    ) {
        $this->beConstructedWith($translator, $entityManager);
        $this->shouldImplement(RegisterCredentialsSubscriberInterface::class);
    }

    /**
     * @param TranslatorInterface    $translator
     * @param EntityManagerInterface $entityManager
     */
    public function should_listen_to(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager
    ) {
        $this->beConstructedWith($translator, $entityManager);
        $this::getSubscribedEvents()->shouldContain('checkCredentials');
    }

    /**
     * @param TranslatorInterface    $translator
     * @param EntityManagerInterface $entityManager
     * @param FormEvent              $event
     */
    public function should_return_void(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        FormEvent $event
    ) {
        $this->beConstructedWith($translator, $entityManager);
        $this->checkCredentials($event)->shouldReturn(null);
    }
}
