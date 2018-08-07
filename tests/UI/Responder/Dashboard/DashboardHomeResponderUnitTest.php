<?php

declare(strict_types = 1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\UI\Responder\Dashboard;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Dashboard\DashboardHomeResponder;
use App\UI\Responder\Dashboard\Interfaces\DashboardHomeResponderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class DashboardHomeResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class DashboardHomeResponderUnitTest extends TestCase
{
    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->presenter = $this->createMock(PresenterInterface::class);
    }

    public function testItImplements()
    {
        $responder = new DashboardHomeResponder($this->twig, $this->presenter);

        static::assertInstanceOf(
            DashboardHomeResponderInterface::class,
            $responder
        );
    }

    public function testInvokeReturn()
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getLocale')->willReturn('fr');

        $dashboardHomeResponder = new DashboardHomeResponder($this->twig, $this->presenter);

        static::assertInstanceOf(
            Response::class,
            $dashboardHomeResponder($requestMock)
        );
    }
}
