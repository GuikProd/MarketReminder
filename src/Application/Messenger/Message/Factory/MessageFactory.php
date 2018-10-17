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

namespace App\Application\Messenger\Message\Factory;

use App\Application\Messenger\Message\Factory\Interfaces\MessageFactoryInterface;
use App\Application\Messenger\Message\Interfaces\UserMessageInterface;
use App\Application\Messenger\Message\UserMessage;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MessageFactory.
 *
 * @package App\Application\Messenger\Message\Factory
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class MessageFactory implements MessageFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createUserMessage(array $payload = []): UserMessageInterface
    {
        $optionsResolver = new OptionsResolver();
        $this->checkMessagePayload($optionsResolver);

        $payload = $optionsResolver->resolve($payload);

        return new UserMessage($payload);
    }

    /**
     * @inheritdoc
     */
    public function checkMessagePayload(\ArrayAccess $resolver): void
    {
        $resolver->setDefaults([
            '_locale' => null,
            '_topic' => null,
            'id' => null,
            'user' => null
        ]);

        $resolver->setAllowedTypes('_locale', array('string', 'null'));
        $resolver->setAllowedTypes('_topic', array('string', 'null'));
        $resolver->setAllowedTypes('id', array('string', 'null'));
        $resolver->setAllowedTypes('user', array('array', 'null'));
    }
}
