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
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

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

        $redisConnector = new RedisConnector(
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

        $this->redisTranslationRepository = new RedisTranslationRepository($redisConnector);

        $this->redisTranslationWriter = new RedisTranslationWriter($redisConnector);
    }

    public function testItPreventWrongLocale()
    {
        $kernel = static::bootKernel();

        $application = new Application($kernel);

        $application->add(new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'ru',
        ]);

        $display = $commandTester->getDisplay();

        static::assertContains(
            'The locale isn\'t defined in the accepted locales, the generated files could not be available.',
            $display
        );
    }

    public function testItCheckDefaultFiles()
    {
        $kernel = static::bootKernel();

        $application = new Application($kernel);

        $application->add(new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'en',
        ]);

        $display = $commandTester->getDisplay();

        static::assertContains(
            'The default files already contains the translated content, the translation process is skipped.',
            $display
        );
    }

    public function testItDoesNotBackupTheFile()
    {
        $kernel = static::bootKernel();

        $application = new Application($kernel);

        $application->add(new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'en',
        ]);

        $display = $commandTester->getDisplay();

        static::assertNotContains(
            'The default content of the file has been saved in the backup.',
            $display
        );
    }
}
