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

namespace App\Tests\UI\Presenter;

use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationWriterInterface;
use App\Infra\Redis\Translation\CloudTranslationRepository;
use App\Infra\Redis\Translation\CloudTranslationWriter;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PresenterIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class PresenterIntegrationTest extends KernelTestCase
{
    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * @var array
     */
    private $testingData = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->redisTranslationRepository = new CloudTranslationRepository($redisConnector);
        $this->redisTranslationWriter = new CloudTranslationWriter($redisConnector);
        $this->presenter = new Presenter($this->redisTranslationRepository);

        $this->testingData = ['channel' => 'messages', 'key' => 'home.text'];
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItResolveOptionWithoutCache(string $locale, string $channel, array $values)
    {
        $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $locale.'.'.$channel.'.yaml',
            [$values['key'] => $values['value']]
        );

        $presenter = new Presenter($this->redisTranslationRepository);
        $presenter->prepareOptions([
            '_locale' => 'ru',
            'page' => [
                'button' => [
                    'key' => $values['key'],
                    'channel' => $channel
                ]
            ]
        ]);

        static::assertSame($values['key'], $presenter->getPage()['button']['value']);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItResolveOptionsWithCache(string $locale, string $channel, array $values)
    {
        $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $channel.'.'.$locale.'.yaml',
            [$values['key'] => $values['value']]
        );

        $presenter = new Presenter($this->redisTranslationRepository);
        $presenter->prepareOptions([
            '_locale' => $locale,
            'page' => [
                'button' => [
                    'key' => $values['key'],
                    'channel' => $channel
                ]
            ]
        ]);

        static::assertSame($values['value'], $presenter->getPage()['button']['value']);
    }
    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', ['key' => 'home.text', 'value' => 'Bonjour le monde']);
        yield array('en', 'messages', ['key' => 'home.text', 'value' => 'Hello World !']);
    }
}