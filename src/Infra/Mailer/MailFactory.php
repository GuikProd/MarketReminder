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

namespace App\Infra\Mailer;

use App\Infra\Mailer\Interfaces\MailFactoryInterface;

/**
 * Class MailFactory.
 *
 * @package App\Infra\Mailer
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class MailFactory implements MailFactoryInterface
{
    /**
     * @var string
     */
    private $sender;

    /**
     * @inheritdoc
     */
    public function __construct(string $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @inheritdoc
     */
    public function createMail(array $content): \Swift_Message
    {
        return (new \Swift_Message($content['subject']))
                ->setTo($content['receiver'])
                ->setFrom($this->sender)
                ->setSubject($content['subject'])
                ->setBody($content['body'], 'text/html');
    }
}
