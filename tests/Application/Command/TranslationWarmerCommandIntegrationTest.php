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
use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Client\CloudTranslationClient;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Helper\Factory\CloudTranslationFactory;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Parser\CloudTranslationYamlParser;
use App\Infra\GCP\CloudTranslation\Helper\Validator\CloudTranslationValidator;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;
use App\Infra\GCP\Loader\CredentialsLoader;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class TranslationWarmerCommandIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class TranslationWarmerCommandIntegrationTest extends KernelTestCase
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
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationClient;

    /**
     * @var CloudTranslationFactoryInterface
     */
    private $cloudTranslationFactory;

    /**
     * @var CloudTranslationValidatorInterface
     */
    private $cloudTranslationValidator;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $cloudTranslationWriter;

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

        $this->acceptedLocales = getenv('ACCEPTED_LOCALES');
        $this->acceptedChannels = getenv('ACCEPTED_CHANNELS');
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $loader = new CredentialsLoader();
        $parser = new CloudTranslationYamlParser();

        $cloudTranslationBridge = new CloudTranslationBridge(
            'credentials.json',
            __DIR__.'./../../_credentials',
            $loader
        );

        $this->connector = new FileSystemConnector('test');

        $this->cloudTranslationClient = new CloudTranslationClient($cloudTranslationBridge);
        $this->cloudTranslationFactory = new CloudTranslationFactory();
        $this->redisTranslationRepository = new CloudTranslationRepository($this->connector);
        $this->cloudTranslationValidator = new CloudTranslationValidator();
        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->connector,
            $this->cloudTranslationFactory,
            $this->cloudTranslationValidator
        );

        $this->cloudTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationClient,
            $this->redisTranslationRepository,
            $this->cloudTranslationWriter,
            $parser,
            $this->translationsFolder
        );

        $this->application = new Application(static::$kernel);
        $this->application->add(new TranslationWarmerCommand($this->cloudTranslationWarmer));
        $command = $this->application->find('app:translation-warm');
        $this->commandTester = new CommandTester($command);

        $this->connector->getAdapter()->clear();
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

    public function testItDoesNotUseCache()
    {
        $this->cloudTranslationWarmer->warmTranslations('messages', 'en');

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
