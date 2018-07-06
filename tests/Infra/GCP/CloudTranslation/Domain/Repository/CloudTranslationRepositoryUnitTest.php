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

namespace App\Tests\Infra\GCP\CloudTranslation\Domain\Repository;

use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
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
    private $connector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $tagAwareAdapter = $this->createMock(TagAwareAdapterInterface::class);
        $this->connector = $this->createMock(ConnectorInterface::class);

        $this->connector->method('getAdapter')->willReturn($tagAwareAdapter);
    }

    public function testItImplements()
    {
        $redisTranslationRepository = new CloudTranslationRepository($this->connector);

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
        $redisTranslationRepository = new CloudTranslationRepository($this->connector);

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
        yield array('validators.fr.yaml');
    }
}
