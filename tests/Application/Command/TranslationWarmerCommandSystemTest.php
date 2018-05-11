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

use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
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

        $this->application = new Application(static::$kernel);
        $command = $this->application->find('app:translation-warm');
        $this->commandTester = new CommandTester($command);

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

        $this->acceptedLocales = static::$kernel->getContainer()->getParameter('accepted_locales');
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $this->redisConnector->getAdapter()->clear();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testBlackfireProfilingWithCacheWrite()
    {
        $configuration = new Configuration();
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 1.5MB', 'Translation command cache write memory usage');
        $configuration->assert('main.network_in < 20kb', 'Translation command network call');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation command HTTP request');

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
     */
    public function testBlackfireProfilingWithoutCacheWrite()
    {
        // Clear the cache in order to optimize the next write and fetch.
        $this->redisConnector->getAdapter()->clear();

        $configuration = new Configuration();
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 1.3MB', 'Translation command cache without write memory usage');
        $configuration->assert('main.network_in < 20kb', 'Translation command network call');
        $configuration->assert('metrics.http.requests.count == 0', 'Translation command HTTP request');

        $this->commandTester->execute([
            'channel' => 'messages',
            'locale' => 'en'
        ]);

        $this->assertBlackfire($configuration, function () {
            $this->commandTester->execute([
                'channel' => 'messages',
                'locale' => 'en'
            ]);
        });
    }
}
