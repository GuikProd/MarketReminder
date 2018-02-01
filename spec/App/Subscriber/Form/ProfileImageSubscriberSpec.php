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
use Symfony\Component\Translation\TranslatorInterface;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;

/**
 * Class ProfileImageSubscriberSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ProfileImageSubscriberSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|TranslatorInterface $translator
     */
    public function it_implements(TranslatorInterface $translator)
    {
        $this->beConstructedWith($translator);
        $this->shouldImplement(ProfileImageSubscriberInterface::class);
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function should_return_events(TranslatorInterface $translator)
    {
        $this->beConstructedWith($translator);
        $this::getSubscribedEvents()->shouldBeArray();
        $this::getSubscribedEvents()->shouldContain('onSubmit');
    }

    /**
     * @param TranslatorInterface $translator
     *
     * @param FormEvent $event
     */
    public function should_return_void(TranslatorInterface $translator, FormEvent $event)
    {
        $this->beConstructedWith($translator);
        $this->onSubmit($event)->shouldReturn(null);
    }
}
