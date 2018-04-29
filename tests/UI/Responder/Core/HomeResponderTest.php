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

namespace App\Tests\UI\Responder\Core;

use App\UI\Presenter\Core\HomePresenter;
use App\UI\Presenter\Core\Interfaces\HomePresenterInterface;
use App\UI\Responder\Core\HomeResponder;
use App\UI\Responder\Core\Interfaces\HomeResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeResponderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeResponderTest extends TestCase
{
    /**
     * @var HomePresenterInterface
     */
    private $homePresenter;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->homePresenter = new HomePresenter();
        $this->twig = $this->createMock(Environment::class);

        $this->twig->method('getCharset')->willReturn('utf-8');
    }

    public function testItImplements()
    {
        $homeResponder = new HomeResponder(
            $this->twig,
            $this->homePresenter
        );

        static::assertInstanceOf(
            HomeResponderInterface::class,
            $homeResponder
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testResponseIsReturned()
    {
        $requestMock = $this->createMock(Request::class);

        $homeResponder = new HomeResponder(
            $this->twig,
            $this->homePresenter
        );

        static::assertInstanceOf(
            Response::class,
            $homeResponder($requestMock)
        );
    }
}
