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
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        // Used to clear the cache before any test (if not, the cache will always return the same values).
        $this->redisConnector->getAdapter()->clear();

        $this->goodTestingData = [
            'home.text' => 'Inventory management',
            'reset_password.title.text' => 'Réinitialiser votre mot de passe.'
        ];

        $this->redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testBlackfireProfilingItDoesNotSaveSameContentTwice()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 100kb', 'Command memory usage');
        $configuration->assert('main.io < 0.50ms', 'Command IO wait');
        $configuration->assert('main.network_in < 750b', 'Command network call');


        $this->redisTranslationWriter->write(
            'fr',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $this->assertBlackfire($configuration, function () {

            $this->redisTranslationWriter->write(
                'fr',
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
    public function testBlackfireProfilingWithCacheWrite()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kb', 'Command storage memory usage');
        $configuration->assert('main.io < 1ms', 'Command storage IO wait');
        $configuration->assert('main.network_in < 50b', 'Command storage network call');

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationWriter->write(
                'fr',
                'validators.fr.yaml',
                $this->goodTestingData
            );
        });
    }
}
