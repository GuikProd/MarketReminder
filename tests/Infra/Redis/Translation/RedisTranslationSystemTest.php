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

use App\Infra\Redis\Translation\RedisTranslation;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class RedisTranslationSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RedisTranslationSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @group Blackfire
     *
     * @doesNotPerformAssertions
     */
    public function testOptionsResolving()
    {
        $redisTranslation = new RedisTranslation([
            'channel' => 'messages',
            'tag' => 'fr',
            'key' => 'user.creation_success',
            'value' => 'Ce compte a bien été créé.'
        ]);
    }
}
