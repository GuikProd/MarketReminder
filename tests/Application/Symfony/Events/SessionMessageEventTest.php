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

namespace App\Tests\Application\Symfony\Events;

use App\Application\Symfony\Events\SessionMessageEvent;
use PHPUnit\Framework\TestCase;

/**
 * Class SessionMessageEventTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class SessionMessageEventTest extends TestCase
{
    public function testFailureMessage()
    {
        $sessionMessageEvent = new SessionMessageEvent('failure', 'form.username_error');

        static::assertSame(
            'session.message_added',
            $sessionMessageEvent::NAME
        );

        static::assertSame(
            'failure',
            $sessionMessageEvent->getFlashBag()
        );

        static::assertSame(
            'form.username_error',
            $sessionMessageEvent->getMessage()
        );
    }

    public function testSuccessMessage()
    {
        $sessionMessageEvent = new SessionMessageEvent('success', 'user.account_created');

        static::assertSame(
            'session.message_added',
            $sessionMessageEvent::NAME
        );

        static::assertSame(
            'success',
            $sessionMessageEvent->getFlashBag()
        );

        static::assertSame(
            'user.account_created',
            $sessionMessageEvent->getMessage()
        );
    }
}
