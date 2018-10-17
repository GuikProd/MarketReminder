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

use App\Application\Messenger\Message\Interfaces\SessionMessageInterface;
use App\Application\Messenger\Message\SessionMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class SessionMessageUnitTest.
 *
 * @package App\Tests\Application\Messenger\Message
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SessionMessageUnitTest extends TestCase
{
    public function testItExist()
    {
        $message = new SessionMessage('', '');

        static::assertInstanceOf(SessionMessageInterface::class, $message);
    }

    /**
     * @dataProvider provideData
     *
     * @param string $key
     * @param string $message
     *
     * @return void
     */
    public function testItReceiveData(string $key, string $message): void
    {
        $message = new SessionMessage($key, $message);

        static::assertSame($key, $message->getKey());
        static::assertSame($message, $message->getMessage());
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield array('test', 'test');
    }
}
