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

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use App\Tests\TestCase\ConnectorTestCase;

/**
 * Class CloudTranslationWriterUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationWriterUnitTest extends ConnectorTestCase
{
    /**
     * @var CloudTranslationFactoryInterface
     */
    private $cloudTranslationFactory;

    /**
     * @var CloudTranslationValidatorInterface
     */
    private $cloudTranslationValidator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cloudTranslationFactory = $this->createMock(CloudTranslationFactoryInterface::class);
        $this->cloudTranslationValidator = $this->createMock(CloudTranslationValidatorInterface::class);
    }

    public function testItImplementsWithFileSystemConnector()
    {
        $this->createFileSystemConnector();

        $redisTranslationWriter = new CloudTranslationWriter(
            $this->connector,
            $this->cloudTranslationFactory,
            $this->cloudTranslationValidator
        );

        static::assertInstanceOf(
            CloudTranslationWriterInterface::class,
            $redisTranslationWriter
        );
    }

    public function testItImplementsWithRedisConnector()
    {
        $this->createRedisConnector();

        $redisTranslationWriter = new CloudTranslationWriter(
            $this->connector,
            $this->cloudTranslationFactory,
            $this->cloudTranslationValidator
        );

        static::assertInstanceOf(
            CloudTranslationWriterInterface::class,
            $redisTranslationWriter
        );
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
    public function testItStopIfTranslationExistAndIsValidWithFileSystemCache(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createFileSystemConnector();

        $fileSystemWriter = new CloudTranslationWriter(
            $this->connector,
            $this->cloudTranslationFactory,
            $this->cloudTranslationValidator
        );

        $fileSystemWriter->write(
            $locale,
            $channel,
            $channel.'.'.$locale.'.yaml',
            $values
        );

        $processStatus = $fileSystemWriter->write(
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
    public function testItWriteInCache(
        string $locale,
        string $channel,
        string $fileName,
        array $values
    ) {
        $this->createFileSystemConnector();

        $fileSystemWriter = new CloudTranslationWriter(
            $this->connector,
            $this->cloudTranslationFactory,
            $this->cloudTranslationValidator
        );

        $processStatus = $fileSystemWriter->write(
            $locale,
            $channel,
            $fileName,
            $values
        );

        static::assertTrue($processStatus);
    }

    /**
     * @return \Generator
     */
    public function provideRightData()
    {
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.text' => 'Bonjour le monde']);
        yield array('fr', 'messages', 'messages.fr.yaml', ['home.content' => 'Bienvenue sur le contenu.']);
    }
}
