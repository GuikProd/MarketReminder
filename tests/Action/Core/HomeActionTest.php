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

namespace tests\Action\Core;

use Twig\Environment;
use App\Action\Core\HomeAction;
use PHPUnit\Framework\TestCase;
use App\Responder\Core\HomeResponder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeActionTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeActionTest extends TestCase
{
    public function testReturn()
    {
        $twigMock = $this->createMock(Environment::class);

        $homeResponder = new HomeResponder($twigMock);

        $homeAction = new HomeAction();

        static::assertInstanceOf(
            Response::class,
            $homeAction($homeResponder)
        );
    }
}
