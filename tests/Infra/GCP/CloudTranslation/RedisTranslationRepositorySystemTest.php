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

use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationWriterInterface;
use App\Infra\Redis\Translation\CloudTranslationRepository;
use App\Infra\Redis\Translation\CloudTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationRepositorySystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationRepositorySystemTest extends KernelTestCase
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
    public function testBlackfireProfilingAndItReturnNull()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 90kB', 'Repository null call memory usage');
        $configuration->assert('main.network_in < 400B', 'Repository null network call');

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
    public function testBlackfireProfilingAndItReturnAnEntry()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'Repository entries call memory usage');
        $configuration->assert('main.network_in < 400B', 'Repository entries network call');
        $configuration->assert('main.network_out < 90B', 'Repository entries network callees');

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
    public function testBlackfireProfilingAndItReturnASingleEntry()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'Repository single entry call memory usage');
        $configuration->assert('main.network_in < 380B', 'Repository single entry  network call');
        $configuration->assert('main.network_out < 90B', 'Repository single entry network callees');

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
