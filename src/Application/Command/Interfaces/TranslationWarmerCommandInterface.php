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
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationWriterInterface;
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
     * @param string                              $acceptedLocales
     * @param CloudTranslationWarmerInterface     $cloudTranslationWarmer
     * @param RedisTranslationRepositoryInterface $redisTranslationRepository
     * @param RedisTranslationWriterInterface     $redisTranslationWriter
     * @param string                              $translationsFolder
     *
     * {@internal} this command SHOULD call the @see Command constructor
     */
    public function __construct(
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer,
        RedisTranslationRepositoryInterface $redisTranslationRepository,
        RedisTranslationWriterInterface $redisTranslationWriter,
        string $translationsFolder
    );

    /**
     * Allow to warm the Redis cache using the files stored in the templates folder.
     *
     * @param string          $channel     The channel of the file to cache.
     * @param OutputInterface $output      The console output (@see OutputInterface)
     * @param \SplFileInfo    $toWarmFile  The file @see \SplFileInfo which contain the content to cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return bool  If the cache process has succeed.
     */
    public function warmRedisTranslationCache(
        string $channel,
        OutputInterface $output,
        \SplFileInfo $toWarmFile
    ): bool;

    /**
     * @param OutputInterface $output         The console output (@see OutputInterface).
     * @param string          $fileName       The filename to query in cache.
     * @param \SplFileInfo    $toCompareFile  The file to compare against.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return bool  If the cache is valid and can be used for query.
     */
    public function checkRedisTranslationCache(
        OutputInterface $output,
        string $fileName,
        \SplFileInfo $toCompareFile
    ): bool;

    /**
     * Allow to backup every files before it translation, this way,
     * we can check the validity of the file each time.
     *
     * If a file in the backup already contains the content to translate,
     * the backup is considered as fresh.
     *
     * @param OutputInterface $output
     * @param \SplFileInfo    $toBackUpFile
     */
    public function backUpTranslation(
        OutputInterface $output,
        \SplFileInfo $toBackUpFile
    ): void;

    /**
     * Allow to check the content of every file which is already saved in the translations folder.
     *
     * If the content is already in sync with the desired translation, the process is skipped.
     *
     * @param OutputInterface $output
     * @param string          $channel
     * @param string          $locale
     * @param \SplFileInfo    $toCompareFile
     *
     * @return bool if the translation process is skipped or not
     */
    public function checkFileContent(
        OutputInterface $output,
        string $channel,
        string $locale,
        \SplFileInfo $toCompareFile
    ): bool;
}
