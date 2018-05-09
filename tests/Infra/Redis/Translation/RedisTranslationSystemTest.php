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
use Blackfire\Profile\Configuration;
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
     * @requires extension blackfire
     */
    public function testBlackfireProfilingAndOptionsResolving()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 300kb', 'RedisTranslation options resolving memory cost');

        $this->assertBlackfire($configuration, function () {
            $redisTranslation = new RedisTranslation([
                '_locale' => '',
                'channel' => 'messages',
                'tag' => 'fr',
                'key' => 'user.creation_success',
                'value' => 'Ce compte a bien été créé.'
            ]);

            $redisTranslation->getOptions();
        });
    }
}
