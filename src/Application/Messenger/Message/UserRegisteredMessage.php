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

namespace App\Application\Messenger\Message;

use App\Application\Messenger\Message\Interfaces\UserRegisteredMessageInterface;
use App\Domain\Models\Interfaces\UserInterface;

/**
 * Class UserRegisteredMessage.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserRegisteredMessage implements UserRegisteredMessageInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     */
    private $creationDate;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data = [])
    {
        $this->user = $data['user'];
        $this->creationDate = $data['user']->getCreationDate();
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }
}
