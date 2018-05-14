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

namespace App\Tests\Application\Command;

use App\Application\Command\TranslationWarmerCommand;
use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\Infra\Redis\Translation\RedisTranslationWarmer;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class TranslationWarmerCommandSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationWarmerCommandSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $acceptedChannels;

    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var RedisTranslationWarmerInterface
     */
    private $redisTranslationWarmer;

    /**
     * @var RedisTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * @var string
     */
    private $translationsFolder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->acceptedLocales = 'fr|en|it';

        $cloudTranslationBridge = new CloudTranslationBridge(
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename'),
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials')
        );

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );
        $this->cloudTranslationWarmer = new CloudTranslationWarmer($cloudTranslationBridge);
        $this->redisTranslationRepository = new RedisTranslationRepository($this->redisConnector);
        $this->redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $this->acceptedChannels = static::$kernel->getContainer()->getParameter('accepted_channels');
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $this->redisTranslationWarmer = new RedisTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationsFolder
        );

        $this->application = new Application(static::$kernel);
        $this->application->add(new TranslationWarmerCommand($this->redisTranslationWarmer));
        $command = $this->application->find('app:translation-warm');
        $this->commandTester = new CommandTester($command);

        $this->redisConnector->getAdapter()->clear();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testWrongChannelIsUsed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration();
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 280kB', 'Translation command wrong channel memory usage');
        $configuration->assert('main.network_in == 0B', 'Translation command wrong channel network in');
        $configuration->assert('main.network_out == 0B', 'Translation command wrong channel network out');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation command wrong channel  HTTP request');

        $this->assertBlackfire($configuration, function () {
            $this->commandTester->execute([
                'channel' => 'toto',
                'locale' => 'en'
            ]);
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testWrongLocaleIsUsed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 40kB', 'Translation command wrong locale memory usage');
        $configuration->assert('main.network_in == 0B', 'Translation command wrong locale network in');
        $configuration->assert('main.network_out == 0B', 'Translation command wrong locale network out');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation command wrong locale  HTTP request');

        $this->assertBlackfire($configuration, function () {
            $this->commandTester->execute([
                'channel' => 'messages',
                'locale' => 'ru'
            ]);
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testCacheWrite()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 2.1MB', 'Translation command cache write memory usage');
        $configuration->assert('main.network_in < 40B', 'Translation command cache write network in');
        $configuration->assert('main.network_out < 15kB', 'Translation command cache write network out');
        $configuration->assert('metrics.http.requests.count <= 0', 'Translation command cache write  HTTP request');

        $this->assertBlackfire($configuration, function () {
            $this->commandTester->execute([
                'channel' => 'messages',
                'locale' => 'en'
            ]);
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testNoCacheWrite()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 190kB', 'Translation warm no cache memory usage');
        $configuration->assert('main.network_in < 20kB', 'Translation warm no cache network in');
        $configuration->assert('main.network_out < 100B', 'Translation warm no cache network out');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation warm no cache HTTP request');

        $this->redisTranslationWarmer->warmTranslations('messages', 'en');

        $this->assertBlackfire($configuration, function () {
            $this->commandTester->execute([
                'channel' => 'messages',
                'locale' => 'en'
            ]);
        });
    }
}
