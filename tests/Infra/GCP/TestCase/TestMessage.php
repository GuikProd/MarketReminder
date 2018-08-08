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

namespace App\Tests\Infra\GCP\TestCase;

use App\Infra\GCP\CloudPubSub\Message\Interfaces\MessageInterface;

/**
 * Class TestMessage.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class TestMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $topic;

    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return ['test' => 'test'];
    }
}
