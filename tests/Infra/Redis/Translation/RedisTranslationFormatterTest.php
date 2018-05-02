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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationFormatterInterface;
use App\Infra\Redis\Translation\RedisTranslation;
use App\Infra\Redis\Translation\RedisTranslationFormatter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RedisTranslationFormatterTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationFormatterTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * @var array
     */
    private $testingData;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn')
        );

        $this->testingData = [
            'messages' => [
                'fr' => [
                    'home.text' => 'Inventory Management',
                    'password.raw' => 'Mot de passe'

                ]
            ],
            'validators' => [
                'fr' => [
                    '' => ''
                ]
            ]
        ];
    }

    public function testItImplements()
    {
        $redisTranslationFormatter = new RedisTranslationFormatter($this->redisConnector);

        static::assertInstanceOf(
            RedisTranslationFormatterInterface::class,
            $redisTranslationFormatter
        );
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testBlackfireProfilingWithEntryTransformation()
    {
        $redisTranslationFormatter = new RedisTranslationFormatter($this->redisConnector);

        $this->redisConnector->getAdapter()->set(
            'messages',
            $this->testingData
        );

        $probe = static::$blackfire->createProbe();

        $redisTranslationFormatter->formatEntry('messages', 'home.text');

        static::$blackfire->endProbe($probe);
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testBlackfireProfilingWithExceptionAndInvalidKey()
    {
        $this->expectException(\InvalidArgumentException::class);

        $redisTranslationFormatter = new RedisTranslationFormatter($this->redisConnector);

        $this->redisConnector->getAdapter()->set(
            'messages',
            $this->testingData
        );

        $probe = static::$blackfire->createProbe();

        $redisTranslationFormatter->formatEntry('messages', 'home.title');

        static::$blackfire->endProbe($probe);
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testItTransformAskedEntry()
    {
        $redisTranslationFormatter = new RedisTranslationFormatter($this->redisConnector);

        $this->redisConnector->getAdapter()->set(
            'messages',
            $this->testingData
        );

        $storedEntry = $redisTranslationFormatter->formatEntry('messages', 'home.text');

        static::assertInstanceOf(
            RedisTranslation::class,
            $storedEntry
        );
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testItThrownAnExceptionWithInvalidKey()
    {
        $this->expectException(\InvalidArgumentException::class);

        $redisTranslationFormatter = new RedisTranslationFormatter($this->redisConnector);

        $this->redisConnector->getAdapter()->set(
            'messages',
            $this->testingData
        );

        $storedEntry = $redisTranslationFormatter->formatEntry('messages', 'home.title');

        static::assertNotInstanceOf(
            RedisTranslation::class,
            $storedEntry
        );
    }
}
