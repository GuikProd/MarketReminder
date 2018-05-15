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

namespace App\Tests\UI\Presenter\Core;

use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\RedisTranslation;
use App\UI\Presenter\AbstractPresenter;
use App\UI\Presenter\Core\HomePresenter;
use App\UI\Presenter\Core\Interfaces\HomePresenterInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class HomePresenterUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomePresenterUnitTest extends TestCase
{
    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->redisTranslationRepository = $this->createMock(RedisTranslationRepositoryInterface::class);
    }

    public function testItImplementsAndExtends()
    {
        $homePresenter = new HomePresenter($this->redisTranslationRepository);

        static::assertInstanceOf(AbstractPresenter::class, $homePresenter);
        static::assertInstanceOf(HomePresenterInterface::class, $homePresenter);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param $locale
     * @param $channel
     * @param array $values
     */
    public function testItDefineDefaultOptions($locale, $channel, array $values)
    {
        $cacheItems = [];

        foreach ($values as $item => $value) {
            $cacheItems[] = new RedisTranslation([
                '_locale' => $locale,
                'channel' => $channel,
                'tag' => Uuid::uuid4()->toString(),
                'key' => $item,
                'value' => $value
            ]);
        }

        $this->redisTranslationRepository->method('getSingleEntry')->willReturn($cacheItems[0]);

        $homePresenter = new HomePresenter($this->redisTranslationRepository);
        $homePresenter->prepareOptions([
            'page' => [
                'content' => [
                    'title' => 'home.text',
                    'channel' => 'messages',
                    '_locale' => 'fr',
                    'value' => ''
                ]
            ]
        ]);

        static::assertArrayHasKey('content', $homePresenter->getPage());
    }

    /**
     * @dataProvider provideRightData
     *
     * @param $locale
     * @param $channel
     * @param array $values
     */
    public function testItDoesNotFoundContent($locale, $channel, array $values)
    {
        $cacheItems = [];

        foreach ($values as $item => $value) {
            $cacheItems[] = new RedisTranslation([
                '_locale' => $locale,
                'channel' => $channel,
                'tag' => Uuid::uuid4()->toString(),
                'key' => $item,
                'value' => $value
            ]);
        }

        $this->redisTranslationRepository->method('getSingleEntry')->willReturn($cacheItems[0]);

        $homePresenter = new HomePresenter($this->redisTranslationRepository);
        $homePresenter->prepareOptions([
            'page' => [
                'content' => [
                    'title' => 'home.content',
                    'channel' => 'messages',
                    '_locale' => 'fr',
                    'value' => ''
                ]
            ]
        ]);

        static::assertNull($homePresenter->getPage()['content']['value']);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param $locale
     * @param $channel
     * @param array $values
     */
    public function testItCallTheTranslatedContent($locale, $channel, array $values)
    {
        $cacheItems = [];

        foreach ($values as $item => $value) {
            $cacheItems[] = new RedisTranslation([
                '_locale' => $locale,
                'channel' => $channel,
                'tag' => Uuid::uuid4()->toString(),
                'key' => $item,
                'value' => $value
            ]);
        }

        $this->redisTranslationRepository->method('getSingleEntry')->willReturn($cacheItems[0]);

        $homePresenter = new HomePresenter($this->redisTranslationRepository);
        $homePresenter->prepareOptions([
            'page' => [
                'content' => [
                    'title' => 'home.text',
                    'channel' => 'messages',
                    '_locale' => 'fr',
                    'value' => ''
                ]
            ]
        ]);

        static::assertSame($values['home.text'], $homePresenter->getPage()['content']['value']);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', ['home.text' => 'Bonjour le monde']);
    }
}
