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

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\GCP\CloudTranslation\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationWarmer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationWarmerUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWarmerUnitTest extends KernelTestCase
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
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

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

        $this->acceptedChannels = 'messages|validators';
        $this->acceptedLocales = 'fr|en';
        $this->cloudTranslationWarmer = $this->createMock(CloudTranslationWarmerInterface::class);
        $this->redisTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->redisTranslationWriter = $this->createMock(CloudTranslationWriterInterface::class);
        $this->translationsFolder = static::$kernel->getContainer()->getParameter('translator.default_path');
    }

    public function testItImplements()
    {
        $redisTranslationWarmer = new RedisTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationsFolder
        );

        static::assertInstanceOf(
            RedisTranslationWarmerInterface::class,
            $redisTranslationWarmer
        );
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testWrongChannelIsUsed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $redisTranslationWarmer = new RedisTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
            $this->translationsFolder
        );

        $processStatus = $redisTranslationWarmer->warmTranslations('toto', 'en');

        static::assertFalse($processStatus);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testWrongLocaleIsUsed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $redisTranslationWarmer = new RedisTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
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
     * @throws \InvalidArgumentException  {@see RedisTranslationWarmer::warmTranslations()}
     * @throws \Psr\Cache\InvalidArgumentException {@see CacheItemPoolInterface::getItem()}
     */
    public function testCacheIsValid(string $channel, string $locale, array $values)
    {
        $this->redisTranslationRepository->method('getEntries')
                                         ->willReturn($values);

        $this->cloudTranslationWarmer->method('warmArraytranslation')
                                     ->willReturn([]);

        $redisTranslationWarmer = new RedisTranslationWarmer(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->redisTranslationRepository,
            $this->redisTranslationWriter,
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
