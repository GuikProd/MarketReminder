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

use App\Application\Event\User\UserCreatedEvent;
use App\Domain\Models\Interfaces\UserInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UserCreatedEventTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserCreatedEventTest extends TestCase
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

    public function testGetterReturn()
    {
        $event = new UserCreatedEvent($this->user);

        static::assertInstanceOf(UserInterface::class, $event->getUser());
    }
}
