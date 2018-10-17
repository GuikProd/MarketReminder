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

namespace App\Tests\Infra\GCP\CloudPubSub\Receiver;

use App\Infra\GCP\CloudPubSub\Adapter\ReceiverMessengerAdapterInterface;
use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;
use App\Infra\GCP\CloudPubSub\Receiver\CloudPubSubReceiver;
use App\Infra\GCP\CloudPubSub\Receiver\Interfaces\CloudPubSubReceiverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Transport\ReceiverInterface;

/**
 * Class CloudPubSubReceiverUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubReceiverUnitTest extends TestCase
{
    /**
     * @var CloudPubSubClientInterface|null
     */
    private $cloudPubSubClient = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudPubSubClient = $this->createMock(CloudPubSubClientInterface::class);
    }

    public function testItImplements()
    {
        $receiver = new CloudPubSubReceiver($this->cloudPubSubClient);

        static::assertInstanceOf(CloudPubSubReceiverInterface::class, $receiver);
        static::assertInstanceOf(ReceiverMessengerAdapterInterface::class, $receiver);
        static::assertInstanceOf(ReceiverInterface::class, $receiver);
    }
}
