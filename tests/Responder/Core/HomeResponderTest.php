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

namespace tests\Responder\Core;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use App\Responder\Core\HomeResponder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeResponderTest;
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeResponderTest extends TestCase
{
    public function testReturn()
    {
        $environmentMock = $this->createMock(Environment::class);

        $homeResponder = new HomeResponder($environmentMock);

        static::assertInstanceOf(
            Response::class,
            $homeResponder()
        );
    }
}
