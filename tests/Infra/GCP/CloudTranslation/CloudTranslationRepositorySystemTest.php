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
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationRepositorySystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

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

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);
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
