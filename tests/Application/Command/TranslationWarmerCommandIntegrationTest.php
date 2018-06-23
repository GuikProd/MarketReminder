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
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
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
     * @var Application
     */
    private $application;

    /**
     * @var ConnectorInterface
     */
    private $connector;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->connector = static::$container->get(ConnectorInterface::class);
        $this->cloudTranslationWarmer = static::$container->get(CloudTranslationWarmerInterface::class);

        $this->application = new Application(static::$kernel);
        $this->application->add(new TranslationWarmerCommand($this->cloudTranslationWarmer));
        $command = $this->application->find('app:translation-warm');
        $this->commandTester = new CommandTester($command);

        $this->connector->getAdapter()->clear();
    }

    public function testItPreventWrongChannel()
    {
        static::expectException(\InvalidArgumentException::class);

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
        static::expectException(\InvalidArgumentException::class);

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
