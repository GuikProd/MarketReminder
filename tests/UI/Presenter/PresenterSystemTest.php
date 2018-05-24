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
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItResolveOptionsWithoutCache()
    {
        $this->redisTranslationWriter->write(
            'fr',
            $this->testingData['channel'],
            'messages.fr.yaml',
            [$this->testingData['key'] => 'Bonjour le monde']
        );

        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 50kB', 'Presenter content call without cache memory usage');
        $configuration->assert('main.network_in < 15B', 'Presenter content call without cache network in');
        $configuration->assert('main.network_out < 100B', 'Presenter content call without cache network out');

        $this->assertBlackfire($configuration, function () {
            $this->presenter->prepareOptions([
                '_locale' => 'en',
                'page' => [
                    'button' => $this->testingData
                ]
            ]);
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItResolveOptionsWithCache()
    {
        $this->redisTranslationWriter->write(
            'fr',
            $this->testingData['channel'],
            'messages.fr.yaml',
            [$this->testingData['key'] => 'Bonjour le monde']
        );

        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 85kB', 'Presenter content call with cache memory usage');
        $configuration->assert('main.network_in < 400B', 'Presenter content call with cache network in');
        $configuration->assert('main.network_out < 100B', 'Presenter content call with cache network out');

        $this->assertBlackfire($configuration, function () {
            $this->presenter->prepareOptions([
                '_locale' => 'fr',
                'page' => [
                    'button' => $this->testingData
                ]
            ]);
        });
    }
}
