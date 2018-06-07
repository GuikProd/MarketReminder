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
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\Tests\TestCase\ConnectorTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;

/**
 * Class CloudTranslationRepositorySystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationRepositorySystemTest extends ConnectorTestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithFileSystemCacheAndFileSystemBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 18kB', 'Repository null Filesystem memory usage');
        $configuration->assert('main.network_in == 0B', 'Repository null Filesystem network in');
        $configuration->assert('main.network_out == 0B', 'Repository null Filesystem network out');

        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->backUpConnector,
            $this->connector
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationRepository->getEntries('validators.fr.yaml');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithRedisCacheAndRedisBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 60kB', 'Repository null Redis memory usage');
        $configuration->assert('main.network_in < 30B', 'Repository null Redis network in');
        $configuration->assert('main.network_out < 200B', 'Repository null Redis network out');

        $this->createRedisConnector();
        $this->createRedisBackUp();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationRepository->getEntries('validators.fr.yaml');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnAnEntryWithFileSystemCacheAndFileSystemBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 50kB', 'Repository entries call memory Filesystem usage');
        $configuration->assert('main.network_in == 0B', 'Repository entries Filesystem network in');
        $configuration->assert('main.network_out == 0B', 'Repository entries Filesystem network out');

        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationRepository->getEntries('messages.fr.yaml');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnAnEntryWithRedisCacheAndRedisBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'Repository entries call memory Redis usage');
        $configuration->assert('main.network_in < 400B', 'Repository entries Redis network in');
        $configuration->assert('main.network_out < 160B', 'Repository entries Redis network out');

        $this->createRedisConnector();
        $this->createRedisBackUp();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationRepository->getEntries('messages.fr.yaml');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnASingleEntryWithFileSystemCacheAndFileSystemBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 50kB', 'Repository single entry call memory Filesystem usage');
        $configuration->assert('main.network_in == 0B', 'Repository single entry Filesystem network in');
        $configuration->assert('main.network_out == 0B', 'Repository single entry Filesystem network out');

        $this->createFileSystemConnector();
        $this->createFileSystemBackUp();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationRepository->getSingleEntry(
                'messages.fr.yaml',
                'fr',
                'home.text'
            );
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnASingleEntryWithRedisCacheAndRedisBackUp()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'Repository single entry call memory Redis usage');
        $configuration->assert('main.network_in < 380B', 'Repository single entry network Redis network in');
        $configuration->assert('main.network_out < 160B', 'Repository single entry network Redis network out');

        $this->createRedisConnector();
        $this->createRedisBackUp();

        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->cloudTranslationRepository = new CloudTranslationRepository(
            $this->connector,
            $this->backUpConnector
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationRepository->getSingleEntry(
                'messages.fr.yaml',
                'fr',
                'home.text'
            );
        });
    }
}
