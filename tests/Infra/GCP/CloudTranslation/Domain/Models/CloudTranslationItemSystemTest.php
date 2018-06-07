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

namespace App\Tests\Infra\Redis\Translation;

use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationItemSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationItemSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testOptionsResolving()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 300kB', 'CloudTranslationItem options resolving memory cost');

        $this->assertBlackfire($configuration, function () {
            new CloudTranslationItem([
                '_locale' => 'fr',
                'channel' => 'messages',
                'tag' => 'fr',
                'key' => 'user.creation_success',
                'value' => 'Ce compte a bien été créé.'
            ]);
        });
    }
}
