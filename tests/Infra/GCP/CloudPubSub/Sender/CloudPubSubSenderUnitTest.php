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

namespace App\Tests\Infra\GCP\CloudPubSub\Sender;

use App\Infra\GCP\CloudPubSub\Adapter\SenderMessengerAdapterInterface;
use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;
use App\Infra\GCP\CloudPubSub\Sender\CloudPubSubSender;
use App\Infra\GCP\CloudPubSub\Sender\Interfaces\CloudPubSubSenderInterface;
use App\Tests\Infra\GCP\TestCase\WrongTestMessage;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\SenderInterface;

/**
 * Class CloudPubSubSenderUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubSenderUnitTest extends TestCase
{
    /**
     * @var CloudPubSubClientInterface|null
     */
    private $cloudPubSubClient = null;

    /**
     * @var LoggerInterface|null
     */
    private $logger = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudPubSubClient = $this->createMock(CloudPubSubClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testItImplements()
    {
        $sender = new CloudPubSubSender($this->cloudPubSubClient, $this->logger);

        static::assertInstanceOf(CloudPubSubSenderInterface::class, $sender);
        static::assertInstanceOf(SenderMessengerAdapterInterface::class, $sender);
        static::assertInstanceOf(SenderInterface::class, $sender);
    }

    public function testItRefuseToSend()
    {
        static::expectException(\InvalidArgumentException::class);
        static::doesNotPerformAssertions();

        $sender = new CloudPubSubSender($this->cloudPubSubClient, $this->logger);

        $message = new WrongTestMessage();

        $envelope = new Envelope($message);

        $sender->send($envelope);
    }
}
