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

use App\Infra\Redis\Translation\Interfaces\RedisTranslationInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationPresenterInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\RedisTranslation;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class PresenterUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class PresenterUnitTest extends TestCase
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

    public function testItImplements()
    {
        $presenter = new Presenter($this->redisTranslationRepository);

        static::assertInstanceOf(PresenterInterface::class, $presenter);
        static::assertInstanceOf(
            RedisTranslationPresenterInterface::class,
            $presenter
        );
    }

    /**
     * @dataProvider provideRightOptions
     *
     * @param string $locale
     * @param array $values
     */
    public function testItResolveOptionsWithoutCache(string $locale, array $values)
    {
        $presenter = new Presenter($this->redisTranslationRepository);
        $presenter->prepareOptions([
            '_locale' => $locale,
            'page' => $values
        ]);

        static::assertSame($locale, $presenter->getViewOptions()['_locale']);
        static::assertCount(count($values), $presenter->getPage());
    }

    /**
     * @dataProvider provideRedisTranslations
     *
     * @param RedisTranslationInterface $redisTranslation
     */
    public function testItResolveOptionsWithCache(RedisTranslationInterface $redisTranslation)
    {
        $this->redisTranslationRepository->method('getSingleEntry')->willReturn($redisTranslation);

        $presenter = new Presenter($this->redisTranslationRepository);
        $presenter->prepareOptions([
            '_locale' => $redisTranslation->getLocale(),
            'page' => [
                'button' => [
                    'channel' => 'messages',
                    'key' => 'home.text'
                ]
            ]
        ]);

        static::assertSame($redisTranslation->getLocale(), $presenter->getViewOptions()['_locale']);
    }

    /**
     * @return \Generator
     */
    public function provideRightOptions()
    {
        yield array('fr', ['button' => ['channel' => 'messages', 'key' => 'home.text']]);
        yield array('en', ['link' => ['channel' => 'messages', 'key' => 'home.link.welcome']]);
    }

    /**
     * @return \Generator
     */
    public function provideRedisTranslations()
    {
        yield array(new RedisTranslation([
            '_locale' => 'fr',
            'channel' => 'messages',
            'tag' => Uuid::uuid4()->toString(),
            'key' => 'home.text',
            'value' => ''
        ]));
        yield array(new RedisTranslation([
            '_locale' => 'en',
            'channel' => 'messages',
            'tag' => Uuid::uuid4()->toString(),
            'key' => 'home.text',
            'value' => ''
        ]));
    }
}
