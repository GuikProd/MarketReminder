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

namespace spec\App\Subscriber\User;

use Twig\Environment;
use PhpSpec\ObjectBehavior;
use App\Event\User\UserCreatedEvent;
use App\Event\User\UserValidatedEvent;
use App\Subscriber\Interfaces\UserSecuritySubscriberInterface;

/**
 * Class UserSecuritySubscriberSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSecuritySubscriberSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|Environment   $twig
     * @param \PhpSpec\Wrapper\Collaborator|\Swift_Mailer $mailer
     */
    public function it_implements(
        Environment $twig,
        \Swift_Mailer $mailer
    ) {
        $this->beConstructedWith($twig, 'test@test.fr', $mailer);
        $this->shouldImplement(UserSecuritySubscriberInterface::class);
    }

    /**
     * @param Environment   $twig
     * @param \Swift_Mailer $mailer
     */
    public function should_subscribed_to(
        Environment $twig,
        \Swift_Mailer $mailer
    ) {
        $this->beConstructedWith($twig, 'test@test.fr', $mailer);
        $this::getSubscribedEvents()->shouldContain('onUserCreated');
    }

    /**
     * @param Environment      $twig
     * @param \Swift_Mailer    $mailer
     * @param UserCreatedEvent $event
     */
    public function should_send_welcome_email(
        Environment $twig,
        \Swift_Mailer $mailer,
        UserCreatedEvent $event
    ) {
        $this->beConstructedWith($twig, 'test@test.fr', $mailer);
        $this->onUserCreated($event)->shouldReturn(null);
    }

    /**
     * @param Environment $twig
     * @param \Swift_Mailer $mailer
     * @param UserValidatedEvent $event
     */
    public function should_send_post_validation_email(
        Environment $twig,
        \Swift_Mailer $mailer,
        UserValidatedEvent $event
    ) {
        $this->beConstructedWith($twig, 'test@test.fr', $mailer);
        $this->onUserValidated($event)->shouldReturn(null);
    }
}
