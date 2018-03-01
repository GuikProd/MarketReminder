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

use PHPUnit\Framework\TestCase;
use App\Responder\Security\AskResetPasswordResponder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AskResetPasswordResponderTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordResponderTest extends TestCase
{
    public function testInvokeReturn()
    {
        $urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);
        $urlGeneratorMock->method('generate')
                         ->willReturn('/fr/');

        $askResetPasswordResponder = new AskResetPasswordResponder($urlGeneratorMock);

        static::assertInstanceOf(
            RedirectResponse::class,
            $askResetPasswordResponder()
        );

        static::assertSame(
            '/fr/',
            $askResetPasswordResponder($urlGeneratorMock)->getTargetUrl()
        );
    }
}
