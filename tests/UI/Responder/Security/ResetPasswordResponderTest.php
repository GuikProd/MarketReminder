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

namespace App\Tests\UI\Responder\Security;

use App\UI\Responder\Security\Interfaces\ResetPasswordResponderInterface;
use App\UI\Responder\Security\ResetPasswordResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ResetPasswordResponderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordResponderTest extends TestCase
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
    }

    public function testItImplements()
    {
        $resetPasswordResponder = new ResetPasswordResponder($this->urlGenerator);

        static::assertInstanceOf(
            ResetPasswordResponderInterface::class,
            $resetPasswordResponder
        );
    }

    public function testItReturnARedirectResponse()
    {
        $resetPasswordResponder = new ResetPasswordResponder($this->urlGenerator);

        static::assertInstanceOf(
            RedirectResponse::class,
            $resetPasswordResponder()
        );
    }
}
