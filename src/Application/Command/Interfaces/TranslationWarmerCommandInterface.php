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

use App\Infra\Redis\Translation\Interfaces\RedisTranslationWarmerInterface;

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
     * @param RedisTranslationWarmerInterface $redisTranslationWarmer
     *
     * {@internal} this command SHOULD call the @see Command constructor
     */
    public function __construct(RedisTranslationWarmerInterface $redisTranslationWarmer);
}
