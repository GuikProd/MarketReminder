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

namespace App\Tests\UI\Presenter;

use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Helper\Factory\CloudTranslationFactory;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\CloudTranslationValidator;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use PHPUnit\Framework\TestCase;

/**
 * Class PresenterIntegrationTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class PresenterIntegrationTest extends TestCase
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
        $connector = new FileSystemConnector('test');
        $backUpConnector = new FileSystemConnector('backup_test');

        $cloudTranslationFactory = new CloudTranslationFactory();
        $cloudTranslationValidator = new CloudTranslationValidator();

        $this->redisTranslationRepository = new CloudTranslationRepository($connector, $backUpConnector);
        $this->redisTranslationWriter = new CloudTranslationWriter($connector, $cloudTranslationFactory, $cloudTranslationValidator);
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
