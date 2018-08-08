<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Application\Messenger\Message;

use App\Application\Messenger\Message\Interfaces\UserRegisteredMessageInterface;
use App\Application\Messenger\Message\UserRegisteredMessage;
use App\Domain\Models\Interfaces\UserInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UserRegisteredMessageUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserRegisteredMessageUnitTest extends TestCase
{
    public function testItImplements()
    {
        $message = new UserRegisteredMessage();

        static::assertInstanceOf(UserRegisteredMessageInterface::class, $message);
    }

    public function testItContainsData()
    {
        $userMock = $this->createMock(UserInterface::class);

        $userMock->method('getCreationDate')->willReturn('Mer 21 Jul 2018');

        $message = new UserRegisteredMessage([
            'user' => $userMock,
            'creation_Date' => $userMock->getCreationDate()
        ]);

        static::assertInstanceOf(UserInterface::class, $message->getUser());
        static::assertNotNull($message->getCreationDate());
    }
}
