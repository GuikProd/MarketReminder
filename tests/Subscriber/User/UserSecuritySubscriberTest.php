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

namespace tests\Subscriber\User;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use App\Subscriber\User\UserSecuritySubscriber;
use App\Subscriber\Interfaces\UserSecuritySubscriberInterface;

/**
 * Class UserSecuritySubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSecuritySubscriberTest extends TestCase
{
    public function testImplements()
    {
        $twigMock = $this->createMock(Environment::class);
        $swiftMailerMock = $this->createMock(\Swift_Mailer::class);

        $userSecuritySubscriber = new UserSecuritySubscriber($twigMock, '', $swiftMailerMock);

        static::assertInstanceOf(
            UserSecuritySubscriberInterface::class,
            $userSecuritySubscriber
        );
    }
}
