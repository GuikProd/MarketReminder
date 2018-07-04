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

namespace App\Tests\Application\Command;

use App\Application\Command\Interfaces\TranslationWarmerCommandInterface;
use App\Application\Command\TranslationWarmerCommand;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class TranslationWarmerCommandUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class TranslationWarmerCommandUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $acceptedChannels;

    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationWarmer;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var CloudTranslationWriterInterface
     */
    private $redisTranslationWriter;

    /**
     * @var CloudTranslationWarmerInterface
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
        $this->acceptedChannels = 'messages|validators|session|form';
        $this->acceptedLocales = 'fr|en';
        $this->cloudTranslationWarmer = $this->createMock(CloudTranslationClientInterface::class);
        $this->redisTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->redisTranslationWriter = $this->createMock(CloudTranslationWriterInterface::class);
        $this->redisTranslationWarmer = $this->createMock(CloudTranslationWarmerInterface::class);
        $this->translationsFolder = '/tmp/translations';
    }

    public function testItImplements()
    {
        $translationWarmerCommand = new TranslationWarmerCommand(
            $this->acceptedChannels,
            $this->acceptedLocales,
            $this->redisTranslationWarmer
        );

        static::assertInstanceOf(Command::class, $translationWarmerCommand);
        static::assertInstanceOf(
            TranslationWarmerCommandInterface::class,
            $translationWarmerCommand
        );
    }
}
