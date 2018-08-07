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

namespace App\Tests\Infra\GCP\CloudTranslation\Domain\Models;

use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslation;
use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationUnitTest extends TestCase
{
    /**
     * @dataProvider provideData
     *
     * @param string $name
     * @param string $channel
     * @param array $items
     */
    public function testItImplements(string $name, string $channel, array $items)
    {
        $cloudTranslation = new CloudTranslation($name, $channel, $items);

        static::assertInstanceOf(CloudTranslationInterface::class, $cloudTranslation);
    }

    /**
     * @dataProvider provideData
     *
     * @param string $name
     * @param string $channel
     * @param array $items
     */
    public function testValueArePassed(string $name, string $channel, array $items)
    {
        $cloudTranslation = new CloudTranslation($name, $channel, $items);

        static::assertSame($name, $cloudTranslation->getName());
        static::assertSame($channel, $cloudTranslation->getChannel());
        static::assertContains($items[0], $cloudTranslation->getItems());
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield array('messages.fr.yaml', 'messages', [
            new CloudTranslationItem([
                '_locale' => 'fr',
                'channel' => 'messages',
                'tag' => 'home.text',
                'key' => 'home.text',
                'value' => 'Hello World !'
            ])
        ]);
    }
}
