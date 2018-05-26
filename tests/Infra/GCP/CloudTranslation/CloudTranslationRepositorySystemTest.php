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
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\ApcuConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationRepositorySystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationRepositorySystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var ApcuConnectorInterface
     */
    private $apcuConnector;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $apcuTranslationRepository;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $apcuTranslationWriter;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->apcuConnector = new ApcuConnector('test');

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->apcuTranslationWriter = new CloudTranslationWriter($this->apcuConnector);
        $this->redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);

        $this->apcuTranslationRepository = new CloudTranslationRepository($this->apcuConnector);
        $this->redisTranslationRepository = new CloudTranslationRepository($this->redisConnector);

        $this->redisConnector->getAdapter()->clear();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItReturnNullWithAPCu()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 15kB', 'Repository null call memory APCu usage');
        $configuration->assert('main.network_in == 0B', 'Repository null network APCu call');

        $this->apcuTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->assertBlackfire($configuration, function () {
            $this->apcuTranslationRepository->getEntries('validators.fr.yaml');
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

        $this->redisTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

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
    public function testItReturnAnEntryWithAPCu()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 50kB', 'Repository entries call memory APCu usage');
        $configuration->assert('main.network_in == 0B', 'Repository entries network APCu call');
        $configuration->assert('main.network_out == 0B', 'Repository entries network APCu callees');

        $this->apcuTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->assertBlackfire($configuration, function () {
            $this->apcuTranslationRepository->getEntries('messages.fr.yaml');
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
        $configuration->assert('main.network_in < 400B', 'Repository entries network Redis call');
        $configuration->assert('main.network_out < 90B', 'Repository entries network Redis callees');

        $this->redisTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

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
    public function testItReturnASingleEntryWithAPCu()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 50kB', 'Repository single entry call memory APCu usage');
        $configuration->assert('main.network_in == 0B', 'Repository single entry network APCu call');
        $configuration->assert('main.network_out == 0B', 'Repository single entry network APCu callees');

        $this->apcuTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->assertBlackfire($configuration, function () {
            $this->apcuTranslationRepository->getSingleEntry(
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
    public function testItReturnASingleEntryWithRedis()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'Repository single entry call memory Redis usage');
        $configuration->assert('main.network_in < 380B', 'Repository single entry network Redis call');
        $configuration->assert('main.network_out < 90B', 'Repository single entry network Redis callees');

        $this->redisTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationRepository->getSingleEntry(
                'messages.fr.yaml',
                'fr',
                'home.text'
            );
        });
    }
}
