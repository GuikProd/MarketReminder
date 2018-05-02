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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationWriterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationWriterTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $cacheTag;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var array
     */
    private $goodTestingData = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn')
        );

        // Used to clear the cache before any test (if not, the cache will return always the same values).
        $this->redisConnector->getAdapter()->clear();

        $this->cacheTag = md5(str_rot13((string) uniqid()));

        $this->goodTestingData = [
            'home.text' => 'Inventory management'
        ];
    }

    public function testItImplements()
    {
        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        static::assertInstanceOf(
            RedisTranslationWriterInterface::class,
            $redisTranslationWriter
        );
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testBlackfireProfilingWithoutCacheWriteAndWrongTag()
    {
        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $cacheTag = $this->cacheTag;

        $redisTranslationWriter->write(
            $this->cacheTag,
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $probe = static::$blackfire->createProbe();

        $redisTranslationWriter->write(
            $cacheTag,
            'messages.fr.yaml',
            $this->goodTestingData
        );

        static::$blackfire->endProbe($probe);
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testBlackfireProfilingWithCacheWrite()
    {
        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $probe = static::$blackfire->createProbe();

        $redisTranslationWriter->write(
            $this->cacheTag,
            'messages.fr.yaml',
            $this->goodTestingData
        );

        static::$blackfire->endProbe($probe);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToSaveWithSameTag()
    {
        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $cacheTag = $this->cacheTag;

        $redisTranslationWriter->write(
            $this->cacheTag,
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $processStatus = $redisTranslationWriter->write(
            $cacheTag,
            'messages.fr.yaml',
            $this->goodTestingData
        );

        static::assertFalse($processStatus);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItRefuseToSaveWithSameFileName()
    {
        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $cacheTag = md5($this->cacheTag);

        $redisTranslationWriter->write(
            $this->cacheTag,
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $processStatus = $redisTranslationWriter->write(
            $cacheTag,
            'messages.fr.yaml',
            $this->goodTestingData
        );

        static::assertFalse($processStatus);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testItSaveEntries()
    {
        $redisTranslationWriter = new RedisTranslationWriter($this->redisConnector);

        $cacheTag = md5($this->cacheTag);

        $processStatus = $redisTranslationWriter->write(
            $cacheTag,
            'validators.fr.yaml',
            $this->goodTestingData
        );

        static::assertTrue($processStatus);
        static::assertNotEmpty($redisTranslationWriter->getTags());
    }
}
