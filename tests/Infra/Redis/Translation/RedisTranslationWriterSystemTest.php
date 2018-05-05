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
use App\Infra\Redis\Translation\RedisTranslationWriter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var RedisConnectorInterface
     */
    private $redisConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->serializer = static::$kernel->getContainer()->get('serializer');

        $this->redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        // Used to clear the cache before any test (if not, the cache will return always the same values).
        $this->redisConnector->getAdapter()->clear();

        $this->goodTestingData = [
            'home.text' => 'Inventory management',
            'reset_password.title.text' => 'Réinitialiser votre mot de passe.'
        ];
    }

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testBlackfireProfilingItDoesNotSaveSameContentTwice()
    {
        $redisTranslationWriter = new RedisTranslationWriter(
            $this->serializer,
            $this->redisConnector
        );

        $redisTranslationWriter->write(
            'fr',
            'messages.fr.yaml',
            $this->goodTestingData
        );

        $probe = static::$blackfire->createProbe();

        $redisTranslationWriter->write(
            'fr',
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
        $redisTranslationWriter = new RedisTranslationWriter(
            $this->serializer,
            $this->redisConnector
        );

        $probe = static::$blackfire->createProbe();

        $redisTranslationWriter->write(
            'fr',
            'validators.fr.yaml',
            $this->goodTestingData
        );

        static::$blackfire->endProbe($probe);
    }
}
