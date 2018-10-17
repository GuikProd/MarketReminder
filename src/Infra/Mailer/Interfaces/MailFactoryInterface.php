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

namespace App\Infra\Mailer\Interfaces;

/**
 * Interface MailFactoryInterface.
 *
 * @package App\Infra\Mailer\Interfaces
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface MailFactoryInterface
{
    /**
     * MailFactoryInterface constructor.
     *
     * @param string $sender
     */
    public function __construct(string $sender);

    /**
     * @param array $content  The mail content.
     *
     * @return \Swift_Message The actual mail.
     */
    public function createMail(array $content): \Swift_Message;
}
