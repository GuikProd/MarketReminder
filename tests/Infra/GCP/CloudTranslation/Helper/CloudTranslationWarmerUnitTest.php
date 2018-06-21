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

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWarmer;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWarmerUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWarmerUnitTest extends KernelTestCase
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
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationBackupWriterInterface
     */
    private $cloudTranslationBackUpWriter;

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

        $this->acceptedChannels = 'messages|validators';
        $this->acceptedLocales = 'fr|en';
        $this->cloudTranslationWarmer = $this->createMock(CloudTranslationClientInterface::class);
        $this->redisTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->cloudTranslationBackUpWriter = $this->createMock(CloudTranslationBackupWriterInterface::class);
        $this->cloudTranslationWriter = $this->createMock(CloudTranslationWriterInterface::class);
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');
    }

    public function testItImplements()
    {
        $redisTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        static::assertInstanceOf(
            CloudTranslationWarmerInterface::class,
            $redisTranslationWarmer
        );
    }

    public function testWrongChannelIsUsed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $redisTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $redisTranslationWarmer->warmTranslations('toto', 'en');

        static::assertFalse($processStatus);
    }

    public function testWrongLocaleIsUsed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $redisTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $redisTranslationWarmer->warmTranslations('messages', 'it');

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightChannelsAndLocalesToCheck
     *
     * @param string $channel
     * @param string $locale
     * @param array  $values
     *
     * @throws \InvalidArgumentException  {@see CloudTranslationWarmer::warmTranslations()}
     */
    public function testCacheIsValid(string $channel, string $locale, array $values)
    {
        $this->redisTranslationRepository->method('getEntries')
                                         ->willReturn($values);

        $this->cloudTranslationWarmer->method('translateArray')->willReturn([]);

        $redisTranslationWarmer = new CloudTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->cloudTranslationBackUpWriter,
            $this->cloudTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $redisTranslationWarmer->warmTranslations($channel, $locale);

        static::assertTrue($processStatus);
    }

    /**
     * {@internal}
     *
     * @return \Generator
     */
    public function provideRightChannelsAndLocalesToCheck()
    {
        yield array('messages', 'en', [
            'messages.en.yaml' => new CloudTranslationItem([
                '_locale' => 'en',
                'channel' => 'messages',
                'tag' => 'dedede',
                'key' => 'home.text',
                'value' => 'Hello World'
            ])
        ]);
        yield array('validators', 'fr', [
            'validators.fr.yaml' => new CloudTranslationItem([
                '_locale' => 'fr',
                'channel' => 'validators',
                'tag' => 'dzdddz',
                'key' => 'home.text',
                'value' => 'Bonjour le monde'])
        ]);
    }

    /**
     * {@internal}
     *
     * @return \Generator
     */
    public function provideWrongChannelsAndLocalesToCheck()
    {
        yield array('messages', 'it', ['home.text' => 'Hello World']);
        yield array('validators', 'ru', ['home.text' => 'daaddaz']);
    }
}
