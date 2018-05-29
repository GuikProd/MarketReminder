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

namespace App\Tests\Application\Event\User;

use App\Application\Event\UserEvent;
use App\Domain\Event\Interfaces\UserEventInterface;
use App\Domain\Models\Interfaces\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEventUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserEventUnitTest extends TestCase
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->user = $this->createMock(UserInterface::class);
    }

    public function testItImplements()
    {
        $event = new UserEvent($this->user);

        static::assertInstanceOf(UserEventInterface::class, $event);
        static::assertInstanceOf(Event::class, $event);
    }
}
