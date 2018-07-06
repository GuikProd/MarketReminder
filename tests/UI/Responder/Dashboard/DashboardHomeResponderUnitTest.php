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

namespace App\Tests\UI\Responder\Dashboard;

use App\UI\Responder\Dashboard\DashboardHomeResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class DashboardHomeResponderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class DashboardHomeResponderUnitTest extends TestCase
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
    }

    public function testInvokeReturn()
    {
        $dashboardHomeResponder = new DashboardHomeResponder($this->twig);

        static::assertInstanceOf(Response::class, $dashboardHomeResponder());
    }
}
