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

namespace tests\UI\Responder\Security;

use App\UI\Responder\Security\Interfaces\ValidationTokenResponderInterface;
use App\UI\Responder\Security\ValidationTokenResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ValidationTokenResponderTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class ValidationTokenResponderTest extends TestCase
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
        $this->urlGenerator->method('generate')->willReturn('/fr/');
    }

    public function testItImplements()
    {
        $validationTokenResponder = new ValidationTokenResponder(
            $this->urlGenerator
        );

        static::assertInstanceOf(
            ValidationTokenResponderInterface::class,
            $validationTokenResponder
        );
    }

    public function testItReturnARedirectResponse()
    {
        $validationTokenResponder = new ValidationTokenResponder(
            $this->urlGenerator
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $validationTokenResponder()
        );
    }
}
