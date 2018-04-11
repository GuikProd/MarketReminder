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

namespace tests\Application\Command;

use App\Application\Command\TranslationWarmerCommand;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class TranslationWarmerCommandTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class TranslationWarmerCommandTest extends KernelTestCase
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
     * @var string
     */
    private $translationFolder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->acceptedLocales = static::$kernel->getContainer()->getParameter('accepted_locales');
        $this->cloudTranslationWarmer = $this->createMock(CloudTranslationWarmerInterface::class);
        $this->translationFolder = static::$kernel->getContainer()->getParameter('translator.default_path');
    }

    public function testItExtends()
    {
        $translationWarmerCommand = new TranslationWarmerCommand(
            $this->acceptedLocales,
            $this->cloudTranslationWarmer,
            $this->translationFolder
        );

        static::assertInstanceOf(
            Command::class,
            $translationWarmerCommand
        );
    }
}