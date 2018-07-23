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

namespace App\Tests\UI\Action\Dashboard;

use App\UI\Action\Dashboard\DashboardHomeAction;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Responder\Dashboard\DashboardHomeResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class DashboardHomeActionUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class DashboardHomeActionUnitTest extends TestCase
{
    /**
     * @var Environment|null
     */
    private $twig = null;

    /**
     * @var PresenterInterface|null
     */
    private $presenter = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->presenter = $this->createMock(PresenterInterface::class);
    }

    public function testInvokeReturn()
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getLocale')->willReturn('fr');

        $dashboardHomeAction = new DashboardHomeAction();

        $dashboardHomeResponder = new DashboardHomeResponder(
            $this->twig,
            $this->presenter
        );

        static::assertInstanceOf(
            Response::class,
            $dashboardHomeAction($requestMock, $dashboardHomeResponder)
        );
    }
}
