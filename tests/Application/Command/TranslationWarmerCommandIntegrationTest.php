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

use App\Application\Command\Interfaces\TranslationWarmerCommandInterface;
use App\Application\Command\TranslationWarmerCommand;
use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
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
     * @var TranslationWarmerCommandInterface
     */
    private $command;

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
    private $translationFolder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->application = new Application(static::$kernel);

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->acceptedLocales = static::$kernel->getContainer()->getParameter('accepted_locales');
        $this->translationFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            new CloudTranslationBridge(
                static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename'),
                static::$kernel->getContainer()->getParameter('cloud.translation_credentials')
            )
        );

        $this->redisTranslationRepository = new RedisTranslationRepository($this->redisConnector);
        $this->redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $this->application->add(new TranslationWarmerCommand(
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationFolder
        ));
        $this->command = $this->application->find('app:translation-warm');
        $this->commandTester = new CommandTester($this->command);

        $this->redisConnector->getAdapter()->clear();
    }

    public function testItPreventWrongChannel()
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
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
        $this->commandTester->execute([
            'command' => $this->command->getName(),
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
            'command' => $this->command->getName(),
            'channel' => 'messages',
            'locale' => 'en',
        ]);

        static::assertContains('The translations are been cached.', $this->commandTester->getDisplay());
    }

    public function testItDoesNotBackupTheFile()
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'channel' => 'messages',
            'locale' => 'en',
        ]);

        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'channel' => 'messages',
            'locale' => 'en',
        ]);

        $display = $this->commandTester->getDisplay();

        static::assertContains(
            'The translations are already cached, process skipped.',
            $display
        );
    }
}
