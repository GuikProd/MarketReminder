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

namespace App\Tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\Client\CloudTranslationClient;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use Google\Cloud\Translate\TranslateClient;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationClientUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationClientUnitTest extends TestCase
{
    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationBridge = $this->createMock(CloudTranslationBridgeInterface::class);

        $this->cloudTranslationBridge->method('getTranslateClient')
                                     ->willReturn($this->createMock(TranslateClient::class));
    }

    public function testItImplements()
    {
        $cloudTranslationHelper = new CloudTranslationClient($this->cloudTranslationBridge);

        static::assertInstanceOf(
            CloudTranslationClientInterface::class,
            $cloudTranslationHelper
        );
    }
}
