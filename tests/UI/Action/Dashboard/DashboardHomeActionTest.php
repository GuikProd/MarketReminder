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

namespace App\Tests\UI\Action\Dashboard;

use App\UI\Action\Dashboard\DashboardHomeAction;
use App\UI\Responder\Dashboard\DashboardHomeResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

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
