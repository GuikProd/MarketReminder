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

use App\Responder\Core\HomeResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeResponderTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomeResponderTest extends TestCase
{
    public function testResponseIsReturned()
    {
        $twigMock = $this->createMock(Environment::class);

        $homeResponder = new HomeResponder($twigMock);

        static::assertInstanceOf(
            Response::class,
            $homeResponder()
        );
    }
}
