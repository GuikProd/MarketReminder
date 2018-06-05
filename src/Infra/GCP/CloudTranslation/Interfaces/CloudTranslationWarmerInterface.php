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

namespace App\Infra\GCP\CloudTranslation\Interfaces;

/**
 * Interface CloudTranslationWarmerInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationWarmerInterface
{
    /**
     * CloudTranslationWarmerInterface constructor.
     *
     * @param string                                $acceptedChannels
     * @param string                                $acceptedLocales
     * @param CloudTranslationHelperInterface       $cloudTranslationWarmer
     * @param CloudTranslationRepositoryInterface   $cloudTranslationRepository
     * @param CloudTranslationBackupWriterInterface $cloudTranslationBackupWriter
     * @param CloudTranslationWriterInterface       $cloudTranslationWriter
     * @param string                                $translationsFolder
     */
    public function __construct(
        string $acceptedChannels,
        string $acceptedLocales,
        CloudTranslationHelperInterface $cloudTranslationWarmer,
        CloudTranslationRepositoryInterface $cloudTranslationRepository,
        CloudTranslationBackupWriterInterface $cloudTranslationBackupWriter,
        CloudTranslationWriterInterface $cloudTranslationWriter,
        string $translationsFolder
    );

    /**
     * Allow to warm the translations using GCP API Translation.
     *
     * In order to ensure a valid process, the default file '$channel.fr.yaml' is checked every time.
     *
     * @condition If an invalid locale or channel is passed, the process is stopped and an \InvalidArgumentException is throw.
     *
     * @condition If the translations cache isn't up to date, a new item is generated and saved.
     *
     * @condition If the translations cache is up to date, the process continue and the new content is send to
     *            GCP in order to be translated.
     *
     * @param string $channel
     * @param string $locale
     *
     * @throws \InvalidArgumentException  If the channel does not exist.
     * @throws \Psr\Cache\InvalidArgumentException {@see CacheItemPoolInterface::getItem()}
     *
     * @return bool  If the translations has been warmed.
     */
    public function warmTranslations(string $channel, string $locale): bool;

    /**
     * Allow to check the cache content before any writing process.
     *
     * @param string $channel  The channel to use in order to check the cache.
     * @param string $locale   The locale used to check the stored translations.
     * @param array $content   The content to check in the cache.
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return bool  If the cache should be updated or not.
     */
    public function isCacheValid(string $channel, string $locale, array $content): bool;

    /**
     * Allow to check the keys of a given file (if it was already translated).
     *
     * @param string $filename          The filename of the file to check against.
     * @param array $translatedKeys     The translated keys to check against.
     *
     * @return bool  If the file is valid or not (false if the file does not exist).
     */
    public function fileExistAndIsValid(string $filename, array $translatedKeys): bool;
}
