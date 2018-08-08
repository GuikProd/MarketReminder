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

namespace App\Infra\GCP\CloudPubSub\Sender;

use App\Infra\GCP\CloudPubSub\Adapter\SenderMessengerAdapterInterface;
use App\Infra\GCP\CloudPubSub\Client\Interfaces\CloudPubSubClientInterface;
use App\Infra\GCP\CloudPubSub\Message\Interfaces\MessageInterface;
use App\Infra\GCP\CloudPubSub\Sender\Interfaces\CloudPubSubSenderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;

/**
 * Class CloudPubSubSender.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudPubSubSender implements CloudPubSubSenderInterface, SenderMessengerAdapterInterface
{
    /**
     * @var CloudPubSubClientInterface
     */
    private $cloudPubSubClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CloudPubSubClientInterface $cloudPubSubClient,
        LoggerInterface $logger
    ) {
        $this->cloudPubSubClient = $cloudPubSubClient;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Envelope $envelope)
    {
        $message = $envelope->getMessage();

        if (!$this->checkMessage($message)) {
            return;
        }

        $this->cloudPubSubClient->publish($message->getTopic(), $message->getIdentifier(), $message);
    }

    /**
     * {@inheritdoc}
     */
    public function checkMessage(object $message): bool
    {
        try {
            if (!$message instanceof MessageInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The message should be a instance of %s, given %s',
                        MessageInterface::class, get_class($message))
                );
            }
        } catch (\InvalidArgumentException $exception) {

            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }
}
