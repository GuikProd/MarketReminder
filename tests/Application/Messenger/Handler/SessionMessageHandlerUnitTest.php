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

namespace App\Tests\Application\Messenger\Handler;

use App\Application\Messenger\Handler\Interfaces\SessionMessageHandlerInterface;
use App\Application\Messenger\Handler\SessionMessageHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SessionMessageHandlerUnitTest.
 *
 * @package App\Tests\Application\Messenger\Handler
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class SessionMessageHandlerUnitTest extends TestCase
{
    /**
     * @var SessionInterface|null
     */
    private $session = null;

    /**
     * @var TranslatorInterface|null
     */
    private $translator = null;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->translator = $this->createMock(TranslatorInterface::class);
    }

    public function testItExist()
    {
        $handler = new SessionMessageHandler($this->session, $this->translator);

        static::assertInstanceOf(SessionMessageHandlerInterface::class, $handler);
    }
}
