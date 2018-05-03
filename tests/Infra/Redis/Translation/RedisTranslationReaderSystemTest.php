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
use App\Infra\Redis\Translation\RedisTranslationReader;
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationReaderSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationReaderSystemTest extends KernelTestCase
{
    use TestCaseTrait;

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

        $this->redisTranslationWriter = new RedisTranslationWriter(
            $this->redisConnector
        );

        $this->redisConnector->getAdapter()->clear();
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testBlackfireProfilingAndItReturnNull()
    {
        $tagName = md5(str_rot13((string) uniqid()));

        $this->redisTranslationWriter->write(
            $tagName,
            'fr',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $redisTranslationReader = new RedisTranslationReader($this->redisConnector);

        $probe = static::$blackfire->createProbe();

        $redisTranslationReader->getEntry(
            'messages.fr.yaml',
            ['home.text' => 'Hi !']
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
    public function testBlackfireProfilingAndItReturnAnEntry()
    {
        $tagName = md5(str_rot13((string) uniqid()));

        $this->redisTranslationWriter->write(
            $tagName,
            'fr',
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        $redisTranslationReader = new RedisTranslationReader($this->redisConnector);

        $probe = static::$blackfire->createProbe();

        $redisTranslationReader->getEntry(
            'messages.fr.yaml',
            ['home.text' => 'hello !']
        );

        static::$blackfire->endProbe($probe);
    }
}
