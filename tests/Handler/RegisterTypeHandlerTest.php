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

namespace tests\Handler;

use PHPUnit\Framework\TestCase;
use App\FormHandler\RegisterTypeHandler;
use Doctrine\Common\Persistence\ObjectManager;
use App\Builder\Interfaces\UserBuilderInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;

/**
 * Class RegisterTypeHandlerTest
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerTest extends TestCase
{
    public function testItImplement()
    {
        $userBuilderMock = $this->createMock(UserBuilderInterface::class);

        $objectManagerMock = $this->createMock(ObjectManager::class);

        $registerTypeHandler = new RegisterTypeHandler(
            $userBuilderMock,
            $objectManagerMock
        );

        static::assertInstanceOf(
            RegisterTypeHandlerInterface::class,
            $registerTypeHandler
        );
    }
}