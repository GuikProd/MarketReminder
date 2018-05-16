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

use App\Infra\Redis\Interfaces\RedisConnectorInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationWriterSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWriterSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var array
     */
    private $goodTestingData = [];

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

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
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        // Used to clear the cache before any test (if not, the cache will always return the same values).
        $this->redisConnector->getAdapter()->clear();

        $this->goodTestingData = [
            'home.text' => 'Inventory management',
            'reset_password.title.text' => 'Réinitialiser votre mot de passe.'
        ];
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItDoesNotSaveSameContentTwice()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'RedisTranslationWriter no write memory usage');
        $configuration->assert('main.network_in < 710B', 'RedisTranslationWriter no write network call');

        $this->redisTranslationWriter->write(
            'fr',
            'messages',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $this->assertBlackfire($configuration, function () {

            $this->redisTranslationWriter->write(
                'fr',
                'messages',
                'messages.fr.yaml',
                $this->goodTestingData
            );
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testWithCacheWrite()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 70kB', 'RedisTranslationWriter memory usage');
        $configuration->assert('main.network_in < 30B', 'RedisTranslationWriter network in');
        $configuration->assert('main.network_out < 1MB', 'RedisTranslationWriter network out');

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationWriter->write(
                'fr',
                'validators',
                'validators.fr.yaml',
                $this->goodTestingData
            );
        });
    }
}
