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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\Infra\Redis\Translation\RedisTranslationWriter;
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
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var RedisTranslationWriterInterface
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

        $this->redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $this->redisTranslationRepository = new RedisTranslationRepository($this->redisConnector);

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
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 90kb', 'Repository null call memory usage');
        $configuration->assert('main.network_in < 400b', 'Repository null network call');

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
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 80kb', 'Repository entries call memory usage');
        $configuration->assert('main.network_in < 400b', 'Repository entries network call');
        $configuration->assert('main.network_out < 90b', 'Repository entries network callees');

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
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 80kb', 'Repository single entry call memory usage');
        $configuration->assert('main.network_in < 400b', 'Repository single entry  network call');
        $configuration->assert('main.network_out < 90b', 'Repository single entry network callees');

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
