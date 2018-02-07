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

namespace spec\App\Form\Type;

use PhpSpec\ObjectBehavior;
use App\Models\Interfaces\UserInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Subscriber\Interfaces\ProfileImageSubscriberInterface;
use App\Subscriber\Interfaces\RegisterCredentialsSubscriberInterface;

/**
 * Class RegisterTypeSpec.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeSpec extends ObjectBehavior
{
    /**
     * @param ProfileImageSubscriberInterface|\PhpSpec\Wrapper\Collaborator        $profileImageSubscriber
     * @param RegisterCredentialsSubscriberInterface|\PhpSpec\Wrapper\Collaborator $registerCredentialsSubscriber
     */
    public function it_implements(
        ProfileImageSubscriberInterface $profileImageSubscriber,
        RegisterCredentialsSubscriberInterface $registerCredentialsSubscriber
    ) {
        $this->beConstructedWith($profileImageSubscriber, $registerCredentialsSubscriber);
        $this->shouldImplement(FormTypeInterface::class);
    }

    /**
     * @param ProfileImageSubscriberInterface        $profileImageSubscriber
     * @param RegisterCredentialsSubscriberInterface $registerCredentialsSubscriber
     * @param OptionsResolver                        $optionsResolver
     */
    public function should_contain(
        ProfileImageSubscriberInterface $profileImageSubscriber,
        RegisterCredentialsSubscriberInterface $registerCredentialsSubscriber,
        OptionsResolver $optionsResolver
    ) {
        $this->beConstructedWith($profileImageSubscriber, $registerCredentialsSubscriber);
        $this->configureOptions($optionsResolver)->shouldContain(UserInterface::class);
    }
}
