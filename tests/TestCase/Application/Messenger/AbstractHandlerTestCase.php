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

namespace App\Tests\TestCase\Application\Messenger;

use App\Infra\Mailer\Interfaces\MailFactoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * Class HandlerTestCase.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class AbstractHandlerTestCase extends TestCase
{
    /**
     * @var string|null
     */
    protected $emailSender = null;

    /**
     * @var MailFactoryInterface|null
     */
    protected $mailFactory = null;

    /**
     * @var \Swift_Mailer|null
     */
    protected $swiftMailer = null;

    /**
     * @var Environment|null
     */
    protected $twig = null;

    /**
     * @var PresenterInterface|null
     */
    protected $presenter = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->emailSender = 'test@gmail.com';
        $this->mailFactory = $this->createMock(MailFactoryInterface::class);
        $this->swiftMailer = $this->createMock(\Swift_Mailer::class);
        $this->twig = $this->createMock(Environment::class);
        $this->presenter = $this->createMock(PresenterInterface::class);
    }
}
