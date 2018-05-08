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
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
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
    private $translationsFolder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $cloudTranslationBridge = new CloudTranslationBridge(
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials.filename'),
            static::$kernel->getContainer()->getParameter('cloud.translation_credentials')
        );

        $redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->acceptedLocales = static::$kernel->getContainer()->getParameter('accepted_locales');
        $this->cloudTranslationWarmer = new CloudTranslationWarmer($cloudTranslationBridge);
        $this->redisTranslationRepository = new RedisTranslationRepository(
            $redisConnector
        );
        $this->redisTranslationWriter = new RedisTranslationWriter(
            $redisConnector
        );
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');

        $redisConnector->getAdapter()->clear();
    }

    /**
     * @group Blackfire
     */
    public function testBlackfireProfilingWithCacheWrite()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $command = $application->find('app:translation-warm');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'channel' => 'messages',
            'locale' => 'en'
        ]);

        $display = $commandTester->getDisplay();
    }
}
