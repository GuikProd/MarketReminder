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

namespace tests\Application\Command;

use App\Application\Command\TranslationWarmerCommand;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
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
        $this->cloudTranslationWarmer = $this->createMock(CloudTranslationWarmerInterface::class);
        $this->translationFolder = static::$kernel->getContainer()->getParameter('translator.default_path');
    }

    public function testItExtends()
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
}
