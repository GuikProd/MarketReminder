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

namespace App\Application\Command\Interfaces;

use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWarmerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface TranslationWarmerCommandInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface TranslationWarmerCommandInterface
{
    /**
     * TranslationWarmerCommandInterface constructor.
     *
     * @param string                           $acceptedLocales
     * @param CloudTranslationWarmerInterface  $cloudTranslationWarmer
     * @param string                           $translationsFolder
     *
     * @internal This command SHOULD call the Command constructor.
     */
    public function __construct(
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer,
        string $translationsFolder
    );

    /**
     * Allow to backup every files before it translation, this way,
     * we can check the validity of the file each time.
     *
     * If a file in the backup already contains the content to translate,
     * the backup is considered as fresh.
     *
     * @param OutputInterface  $output
     * @param \SplFileInfo     $toBackUpFile
     */
    public function backUpTranslation(OutputInterface $output, \SplFileInfo $toBackUpFile): void;
}
