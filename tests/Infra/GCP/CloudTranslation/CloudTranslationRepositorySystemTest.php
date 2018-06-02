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
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use App\Tests\TestCase\ConnectorTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;

/**
 * Class CloudTranslationRepositorySystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationRepositorySystemTest extends ConnectorTestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $cloudTranslationWriter;

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithFileSystem()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 15kB', 'Repository null Filesystem memory usage');
        $configuration->assert('main.network_in == 0B', 'Repository null Filesystem network in');
        $configuration->assert('main.network_out == 0B', 'Repository null Filesystem network out');

        $this->createFileSystemCacheAndFileSystemBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );
        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->redisTranslationRepository = new CloudTranslationRepository($this->connector);

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationRepository->getEntries('validators.fr.yaml');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithRedis()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 90kB', 'Repository null call memory redis usage');
        $configuration->assert('main.network_in < 400B', 'Repository null network redis call');

        $this->createRedisCacheAndRedisBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );
        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->redisTranslationRepository = new CloudTranslationRepository($this->connector);

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationRepository->getEntries('validators.fr.yaml');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnAnEntryWithFileSystem()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 50kB', 'Repository entries call memory FileSystem usage');
        $configuration->assert('main.network_in == 0B', 'Repository entries FileSystem network in');
        $configuration->assert('main.network_out == 0B', 'Repository entries FileSystem network out');

        $this->createFileSystemCacheAndFileSystemBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );
        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->redisTranslationRepository = new CloudTranslationRepository($this->connector);

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationRepository->getEntries('messages.fr.yaml');
        });
    }


    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnAnEntryWithRedis()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'Repository entries call memory Redis usage');
        $configuration->assert('main.network_in < 400B', 'Repository entries Redis network in');
        $configuration->assert('main.network_out < 90B', 'Repository entries Redis network out');

        $this->createRedisCacheAndRedisBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );
        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->redisTranslationRepository = new CloudTranslationRepository($this->connector);

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationRepository->getEntries('messages.fr.yaml');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnASingleEntryWithRedis()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'Repository single entry call memory Redis usage');
        $configuration->assert('main.network_in < 380B', 'Repository single entry network Redis call');
        $configuration->assert('main.network_out < 90B', 'Repository single entry network Redis callees');

        $this->createRedisCacheAndRedisBackUp();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->cloudTranslationBackupWriter,
            $this->connector
        );
        $this->cloudTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->redisTranslationRepository = new CloudTranslationRepository($this->connector);

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationRepository->getSingleEntry(
                'messages.fr.yaml',
                'fr',
                'home.text'
            );
        });
    }
}
