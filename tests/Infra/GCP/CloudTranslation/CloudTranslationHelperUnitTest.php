<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Google\Cloud\Translate\TranslateClient;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationHelperUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationHelperUnitTest extends TestCase
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
}
