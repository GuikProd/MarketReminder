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

namespace App\Tests\Application\Messenger\Message\Factory;

use App\Application\Messenger\Message\Factory\Interfaces\MessageFactoryInterface;
use App\Application\Messenger\Message\Factory\MessageFactory;
use App\Application\Messenger\Message\Interfaces\UserMessageInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageFactoryUnitTest.
 *
 * @package App\Tests\Application\Messenger\Message\Factory
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class MessageFactoryUnitTest extends TestCase
{
    public function testItExist()
    {
        $factory = new MessageFactory();

        static::assertInstanceOf(MessageFactoryInterface::class, $factory);
    }

    /**
     * @dataProvider providePayload
     *
     * @param array $data
     *
     * @return void
     */
    public function testItCreateMessage(array $data): void
    {
        $factory = new MessageFactory();

        $message = $factory->createUserMessage($data);

        static::assertInstanceOf(UserMessageInterface::class, $message);
    }

    /**
     * @return \Generator
     */
    public function providePayload()
    {
        yield array([
            '_locale' => 'fr',
            '_topic' => 'registration',
            'id' => 'toto',
            'user' => [
                'username' => 'toto'
            ]
        ]);
    }
}
