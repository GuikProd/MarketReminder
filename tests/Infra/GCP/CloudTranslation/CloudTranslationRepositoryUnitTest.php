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

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Class CloudTranslationRepositoryUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationRepositoryUnitTest extends TestCase
{
    /**
     * @var ApcuConnectorInterface
     */
    private $apcuConnector;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $tagAwareAdapter = $this->createMock(TagAwareAdapterInterface::class);

        $this->apcuConnector = $this->createMock(ConnectorInterface::class);
        $this->redisConnector = $this->createMock(ConnectorInterface::class);

        $this->apcuConnector->method('getAdapter')->willReturn($tagAwareAdapter);
        $this->redisConnector->method('getAdapter')->willReturn($tagAwareAdapter);
    }

    public function testItImplements()
    {
        $redisTranslationRepository = new CloudTranslationRepository($this->redisConnector);

        static::assertInstanceOf(
            CloudTranslationRepositoryInterface::class,
            $redisTranslationRepository
        );

        $apcuConnector = new CloudTranslationRepository($this->apcuConnector);

        static::assertInstanceOf(
            CloudTranslationRepositoryInterface::class,
            $apcuConnector
        );
    }

    /**
     * @dataProvider provideFilename
     *
     * @param string $fileName
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithAPCu(string $fileName)
    {
        $apcuTranslationRepository = new CloudTranslationRepository($this->apcuConnector);

        static::assertNull(
            $apcuTranslationRepository->getEntries($fileName)
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
        $redisTranslationRepository = new CloudTranslationRepository($this->redisConnector);

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
