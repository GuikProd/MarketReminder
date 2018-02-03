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
use Symfony\Component\Form\FormTypeInterface;
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
     * @param ProfileImageSubscriberInterface|\PhpSpec\Wrapper\Collaborator $profileImageSubscriber
     * @param RegisterCredentialsSubscriberInterface|\PhpSpec\Wrapper\Collaborator $registerCredentialsSubscriber
     */
    public function it_implement(
        ProfileImageSubscriberInterface $profileImageSubscriber,
        RegisterCredentialsSubscriberInterface $registerCredentialsSubscriber
    ) {
        $this->beConstructedWith($profileImageSubscriber, $registerCredentialsSubscriber);
        $this->shouldImplement(FormTypeInterface::class);
    }
}
