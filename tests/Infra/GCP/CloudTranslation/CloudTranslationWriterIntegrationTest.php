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
use App\Infra\GCP\CloudTranslation\Connector\ApcuConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CloudTranslationWriterIntegrationTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudTranslationWriterIntegrationTest extends KernelTestCase
{
    /**
     * @var ApcuConnectorInterface
     */
    private $apcuConnector;

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

        $this->apcuConnector = new ApcuConnector('test');

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.test_dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->apcuTranslationWriter = new CloudTranslationWriter($this->apcuConnector);
        $this->redisTranslationWriter = new CloudTranslationWriter($this->redisConnector);

        $this->apcuConnector->getAdapter()->clear();
        $this->redisConnector->getAdapter()->clear();
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreWithSameContentAndApcu(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->apcuTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->apcuTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToStoreWithSameContentAndRedis(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->redisTranslationWriter->write($locale, $channel, $fileName, $values);

        $processStatus = $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertFalse($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItSaveEntriesWithAPCu(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $processStatus = $this->apcuTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItSaveEntriesWithRedis(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $processStatus = $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItUpdateAndSaveItemWithAPCu(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->apcuTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        $processStatus = $this->apcuTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            ['user.creation_success' => 'Hello user !']
        );

        static::assertTrue($processStatus);
    }

    /**
     * @dataProvider provideRightData
     *
     * @param string $locale
     * @param string $channel
     * @param string $fileName
     * @param array $values
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItUpdateAndSaveItemWithRedis(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        $processStatus = $this->redisTranslationWriter->write(
            $locale,
            $channel,
            $fileName,
            ['user.creation_success' => 'Hello user !']
        );

        static::assertTrue($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'Inventory management']);
        yield array('fr', 'validators', 'validators.fr.yaml', ['reset_password.title.text' => 'Réinitialiser votre mot de passe.']);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->apcuConnector = null;
        $this->redisConnector = null;
    }
}
