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

use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWriterSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationWriterSystemTest extends KernelTestCase
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
     * @var CloudTranslationWriterInterface
     */
    private $apcuTranslationWriter;

    /**
     * @var CloudTranslationWriterInterface
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

        $this->redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);

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
    public function testItDoesNotSaveSameContentTwiceWithRedis()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 80kB', 'CloudTranslationWriter no write memory redis usage');
        $configuration->assert('main.network_in < 710B', 'CloudTranslationWriter no write network redis call');

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
    public function testWithCacheWriteAndRedisUsage()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 70kB', 'CloudTranslationWriter redis usage memory usage');
        $configuration->assert('main.network_in < 30B', 'CloudTranslationWriter redis usage network in');
        $configuration->assert('main.network_out < 1MB', 'CloudTranslationWriter redis usage network out');

        $this->assertBlackfire($configuration, function () {
            $this->redisTranslationWriter->write(
                'fr',
                'validators',
                'validators.fr.yaml',
                $this->goodTestingData
            );
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->redisConnector = null;
    }
}
