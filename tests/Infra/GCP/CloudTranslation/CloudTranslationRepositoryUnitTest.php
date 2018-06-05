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

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class CloudTranslationRepositoryUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationRepositoryUnitTest extends TestCase
{
    /**
     * @var ConnectorInterface
     */
    private $backUpConnector;

    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $tagAwareAdapter = $this->createMock(TagAwareAdapterInterface::class);
        $this->backUpConnector = $this->createMock(ConnectorInterface::class);
        $this->connector = $this->createMock(ConnectorInterface::class);

        $this->backUpConnector->method('getAdapter')->willReturn($tagAwareAdapter);
        $this->connector->method('getAdapter')->willReturn($tagAwareAdapter);
    }

    public function testItImplements()
    {
        $redisTranslationRepository = new CloudTranslationRepository(
            $this->backUpConnector,
            $this->connector
        );

        static::assertInstanceOf(
            CloudTranslationRepositoryInterface::class,
            $redisTranslationRepository
        );
    }

    /**
     * @dataProvider provideFilename
     *
     * @param string $fileName
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithRedis(string $fileName)
    {
        $redisTranslationRepository = new CloudTranslationRepository(
            $this->backUpConnector,
            $this->connector
        );

        static::assertNull(
            $redisTranslationRepository->getEntries($fileName)
        );
    }
    /**
     * @return \Generator
     */
    public function provideFilename()
    {
        yield array('messages.fr.yaml');
    }
}
