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

namespace tests\Responder\Security;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use App\Responder\Security\RegisterResponder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterResponderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterResponderTest extends TestCase
{
    public function testReturn()
    {
        $environmentMock = $this->createMock(Environment::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $registerResponder = new RegisterResponder($environmentMock);

        static::assertInstanceOf(
            Response::class,
            $registerResponder($formInterfaceMock)
        );
    }
}
