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

namespace tests\Action\Dashboard;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use App\Action\Dashboard\DashboardHomeAction;
use Symfony\Component\HttpFoundation\Response;
use App\Responder\Dashboard\DashboardHomeResponder;

/**
 * Class DashboardHomeActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class DashboardHomeActionTest extends TestCase
{
    public function testInvokeReturn()
    {
        $twigMock = $this->createMock(Environment::class);

        $dashboardHomeAction = new DashboardHomeAction();

        $dashboardHomeResponder = new DashboardHomeResponder($twigMock);

        static::assertInstanceOf(
            Response::class,
            $dashboardHomeAction($dashboardHomeResponder)
        );
    }
}
