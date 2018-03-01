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

namespace tests\FormHandler;

use App\FormHandler\AskResetPasswordTypeHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

/**
 * Class AskResetPasswordTypeHandlerTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeHandlerTest extends TestCase
{
    public function testWrongHandlingProcess()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler($entityManagerMock);

        static::assertFalse(
            $askResetPasswordTypeHandler->handle($formInterfaceMock)
        );
    }

    public function testRightHandlingProcess()
    {
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $formInterfaceMock->method('isValid')->willReturn(true);
        $formInterfaceMock->method('isSubmitted')->willReturn(true);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $askResetPasswordTypeHandler = new AskResetPasswordTypeHandler($entityManagerMock);


        static::assertTrue(
            $askResetPasswordTypeHandler->handle($formInterfaceMock)
        );
    }
}
