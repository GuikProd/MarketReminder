<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>s
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\FormHandler;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\FormHandler\RegisterTypeHandler;
use Symfony\Component\Workflow\Registry;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterTypeHandlerTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerTest extends TestCase
{
    public function testItImplement()
    {
        $registryMock = $this->createMock(Registry::class);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $userPasswordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $registerTypeHandler = new RegisterTypeHandler(
            $registryMock,
            $entityManagerMock,
            $userPasswordEncoderMock
        );

        static::assertInstanceOf(
            RegisterTypeHandlerInterface::class,
            $registerTypeHandler
        );
    }
}
