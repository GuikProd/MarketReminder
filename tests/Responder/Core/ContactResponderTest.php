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
use App\Responder\Core\ContactResponder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactResponderTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ContactResponderTest extends TestCase
{
    public function testInvokeReturn()
    {
        $twigMock = $this->createMock(Environment::class);

        $contactResponder = new ContactResponder($twigMock);

        static::assertInstanceOf(
            Response::class,
            $contactResponder()
        );
    }
}
