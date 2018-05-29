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

namespace App\Tests\Application\Command;

use App\Application\Command\TranslationWarmerCommand;
use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\CloudTranslationHelper;
use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationHelperInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class TranslationWarmerCommandIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
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
     * @var CloudTranslationHelperInterface
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
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $redisTranslationWarmer;

    /**
     * @var CloudTranslationWriterInterface
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
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );
        $this->cloudTranslationWarmer = new CloudTranslationHelper($cloudTranslationBridge);
        $this->redisTranslationRepository = new CloudTranslationRepository($this->redisConnector);
        $this->redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);

        $this->acceptedChannels = static::$kernel->getContainer()->getParameter('accepted_channels');
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $this->redisTranslationWarmer = new CloudTranslationWarmer(
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
