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

namespace App\Infra\GCP\CloudPubSub\Message;

use App\Infra\GCP\CloudPubSub\Message\Interfaces\MessageInterface;

/**
 * Class AbstractMessage.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class AbstractMessage implements MessageInterface
{
    /**
     * @var string
     */
    protected $topic;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->data;
    }
}
