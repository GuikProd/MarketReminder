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

namespace App\Tests\Domain\Event\User;

use App\Application\Event\User\UserValidatedEvent;
use App\Domain\Models\Interfaces\UserInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UserValidatedEventTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserValidatedEventTest extends TestCase
{
    public function testGetterReturn()
    {
        $userMock = $this->createMock(UserInterface::class);

        $event = new UserValidatedEvent($userMock);

        static::assertInstanceOf(
            UserInterface::class,
            $event->getUser()
        );
    }
}
