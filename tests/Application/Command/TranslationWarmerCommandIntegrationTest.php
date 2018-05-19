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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class TranslationWarmerCommandIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationWarmerCommandIntegrationTest extends KernelTestCase
{
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

    public function testItPreventWrongChannel()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->commandTester->execute([
            'channel' => 'toto',
            'locale' => 'en',
        ]);

        $display = $this->commandTester->getDisplay();

        static::assertContains(
            'The submitted locale isn\'t supported or the channel does not exist !',
            $display
        );
    }

    public function testItPreventWrongLocale()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->commandTester->execute([
            'channel' => 'messages',
            'locale' => 'ru',
        ]);

        $display = $this->commandTester->getDisplay();

        static::assertContains(
            'The submitted locale isn\'t supported or the channel does not exist !',
            $display
        );
    }

    public function testItWriteTheContentInCache()
    {
        $this->commandTester->execute([
            'channel' => 'messages',
            'locale' => 'fr',
        ]);

        static::assertContains(
            'The warm process is about to begin.',
            $this->commandTester->getDisplay()
        );
        static::assertContains(
            'The warm process is finished.',
            $this->commandTester->getDisplay()
        );
        static::assertNotContains(
            'The translations can\'t be warmed or are already proceed, please retry.',
            $this->commandTester->getDisplay()
        );
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItDoesNotUseCache()
    {
        $this->redisTranslationWarmer->warmTranslations('messages', 'en');

        $this->commandTester->execute([
            'channel' => 'messages',
            'locale' => 'en',
        ]);

        static::assertContains(
            'The warm process is about to begin.',
            $this->commandTester->getDisplay()
        );
        static::assertContains(
            'The warm process is finished.',
            $this->commandTester->getDisplay()
        );
        static::assertNotContains(
            'The translations can\'t be warmed or are already proceed, please retry.',
            $this->commandTester->getDisplay()
        );
    }
}
