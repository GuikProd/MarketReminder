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

namespace App\Application\Command;

use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractTranslationCommand.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class AbstractTranslationCommand extends Command
{
    /**
     * @var string
     */
    private $acceptedChannels;

    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $acceptedChannels,
        string $acceptedLocales
    ) {
        $this->acceptedChannels = $acceptedChannels;
        $this->acceptedLocales = $acceptedLocales;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function checkChannelsAndLocales(string $channel, string $locale): void
    {
        if (!\in_array($channel, explode('|', $this->acceptedChannels))
            || !\in_array($locale, explode('|', $this->acceptedLocales))
        ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The submitted locale is not supported or the channel does not exist, given %s',
                    $locale . ' ' . $channel
                )
            );
        }
    }
}
