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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PresenterSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class PresenterSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

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

        $this->redisTranslationRepository = new RedisTranslationRepository($redisConnector);
        $this->presenter = new Presenter($this->redisTranslationRepository);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @dataProvider provideRightOptionsWithoutCache
     *
     * @param string $locale
     * @param array $values
     */
    public function testItResolveOptionsWithoutCache(string $locale, array $values)
    {
        $configuration = new Configuration();
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 50kB', 'Presenter content call without cache memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->presenter->prepareOptions([
                '_locale' => $locale,
                'page' => $values
            ]);
        });
    }

    /**
     * @return \Generator
     */
    public function provideRightOptionsWithoutCache()
    {
        yield array('fr', ['button' => ['channel' => 'messages', 'key' => 'home.text']]);
        yield array('en', ['link' => ['channel' => 'messages', 'key' => 'home.link.welcome']]);
    }
}
