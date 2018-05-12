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

namespace App\Tests\Application\Command;

use App\Application\Command\Interfaces\TranslationWarmerCommandInterface;
use App\Application\Command\TranslationWarmerCommand;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWarmerInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class TranslationWarmerCommandUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationWarmerCommandUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var RedisTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * @var RedisTranslationWarmerInterface
     */
    private $redisTranslationWarmer;

    /**
     * @var string
     */
    private $translationsFolder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->acceptedLocales = 'fr|en';
        $this->cloudTranslationWarmer = $this->createMock(CloudTranslationWarmerInterface::class);
        $this->redisTranslationRepository = $this->createMock(RedisTranslationRepositoryInterface::class);
        $this->redisTranslationWriter = $this->createMock(RedisTranslationWriterInterface::class);
        $this->redisTranslationWarmer = $this->createMock(RedisTranslationWarmerInterface::class);
        $this->translationsFolder = '/tmp/translations';
    }

    public function testItImplements()
    {
        $translationWarmerCommand = new TranslationWarmerCommand($this->redisTranslationWarmer);

        static::assertInstanceOf(Command::class, $translationWarmerCommand);
        static::assertInstanceOf(
            TranslationWarmerCommandInterface::class,
            $translationWarmerCommand
        );
    }
}
