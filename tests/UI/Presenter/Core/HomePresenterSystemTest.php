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

use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationRepository;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use App\UI\Presenter\Core\HomePresenter;
use App\UI\Presenter\Core\Interfaces\HomePresenterInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class HomePresenterSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class HomePresenterSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var HomePresenterInterface
     */
    private $homePresenter;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var RedisTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->redisTranslationRepository = new RedisTranslationRepository($this->redisConnector);
        $this->redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);
        $this->homePresenter = new HomePresenter($this->redisTranslationRepository);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testTranslationIsNotFound()
    {
        $configuration = new Configuration();
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 100kB', 'HomePresenter content is not found');

        $this->assertBlackfire($configuration, function() {
            $this->homePresenter->prepareOptions([
                'page' => [
                    'title' => [
                        'title' => 'home.text',
                        'channel' => 'messages',
                        '_locale' => 'en',
                        'value' => ''
                    ]
                ]
            ]);
        });
    }
}
