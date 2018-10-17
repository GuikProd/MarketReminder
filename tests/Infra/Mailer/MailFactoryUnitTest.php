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

namespace App\Tests\Infra\Mailer;

use App\Infra\Mailer\Interfaces\MailFactoryInterface;
use App\Infra\Mailer\MailFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class MailFactoryUnitTest.
 *
 * @package App\Tests\Application\Mailer
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class MailFactoryUnitTest extends TestCase
{
    /**
     * @var string|null
     */
    private $sender = null;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->sender = 'test@test.fr';
    }

    public function testItExist()
    {
        $factory = new MailFactory($this->sender);

        static::assertInstanceOf(MailFactoryInterface::class, $factory);
    }

    /**
     * @param array $content
     *
     * @return void
     *
     * @dataProvider provideMailContent
     */
    public function testItCreateMail(array $content): void
    {
        $factory = new MailFactory($this->sender);

        $mail = $factory->createMail($content);

        static::assertInstanceOf(\Swift_Message::class, $mail);
    }

    /**
     * @return \Generator
     */
    public function provideMailContent()
    {
        yield array(['subject' => 'test', 'receiver' => 'test@test.fr', 'body' => 'Random test']);
    }
}
