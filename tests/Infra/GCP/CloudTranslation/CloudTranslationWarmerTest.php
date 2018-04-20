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

namespace tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationWarmerTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationWarmerTest extends TestCase
{
    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->cloudTranslationBridge = $this->createMock(CloudTranslationBridgeInterface::class);
    }

    public function testItImplements()
    {
        $cloudTranslationWarmer = new CloudTranslationWarmer($this->cloudTranslationBridge);

        static::assertInstanceOf(
            CloudTranslationWarmerInterface::class,
            $cloudTranslationWarmer
        );
    }
}
