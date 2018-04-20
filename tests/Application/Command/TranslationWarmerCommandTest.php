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
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class TranslationWarmerCommandTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationWarmerCommandTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

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

        $this->acceptedLocales = static::$kernel->getContainer()->getParameter('accepted_locales');
        $this->translationFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            new CloudTranslationBridge(
                static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename'),
                static::$kernel->getContainer()->getParameter('cloud.translation_credentials')
            )
        );
    }

    public function testItExtendsAndImplements()
    {
        $translationWarmerCommand = new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->translationFolder
        );

        static::assertInstanceOf(
            Command::class,
            $translationWarmerCommand
        );

        static::assertInstanceOf(
            TranslationWarmerCommandInterface::class,
            $translationWarmerCommand
        );
    }

    /**
     * Test if the Translations process is skipped due to the default file content.
     *
     * @group Blackfire
     */
    public function testBlackfireProfilingWithoutTranslation()
    {
        $this->createBlackfire();

        $kernel = static::bootKernel();

        $application = new Application($kernel);

        $application->add(new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);

        $probe = static::$blackfire->createProbe();

        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'en'
        ]);

        static::$blackfire->endProbe($probe);

        $display = $commandTester->getDisplay();

        static::assertContains(
            'The default files already contains the translated content, the translation process is skipped.',
            $display
        );
    }

    /**
     * Test if the backup process is skipped due to the fact that the backup
     * already contain the translated content.
     *
     * @group Blackfire
     */
    public function testBlackfireProfilingWithoutBackup()
    {
        $this->createBlackfire();

        $kernel = static::bootKernel();

        $application = new Application($kernel);

        $application->add(new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);

        $probe = static::$blackfire->createProbe();

        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'ru'
        ]);

        static::$blackfire->endProbe($probe);

        $display = $commandTester->getDisplay();

        static::assertContains(
            'No default file has been found with the translated content, the translation process is in progress.',
            $display
        );

        static::assertNotContains(
            'The default content of the file has been saved in the backup.',
            $display
        );
    }

    public function testItPreventWrongLocale()
    {
        $kernel = static::bootKernel();

        $application = new Application($kernel);

        $application->add(new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'ru'
        ]);

        $display = $commandTester->getDisplay();

        static::assertContains(
            'The locale isn\'t defined in the accepted locales, the generated files could not be available.',
            $display
        );
    }

    public function testItTranslateWithRightChannel()
    {
        $kernel = static::bootKernel();

        $application = new Application($kernel);

        $application->add(new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'en'
        ]);

        $display = $commandTester->getDisplay();

        static::assertContains(
            'The translations has been translated and dumped into the translations folder.',
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
            $this->translationFolder
        ));

        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'channel' => 'messages',
            'locale' => 'en'
        ]);

        $display = $commandTester->getDisplay();

        static::assertNotContains(
            'The default content of the file has been saved in the backup.',
            $display
        );
    }
}
