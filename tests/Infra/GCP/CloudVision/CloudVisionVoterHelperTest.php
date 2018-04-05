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

namespace App\Tests\Infra\GCP\CloudVision;

use App\Infra\GCP\CloudVision\CloudVisionVoterHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudVisionVoterHelperTest;.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class CloudVisionVoterHelperTest extends TestCase
{
    public function testWrongLabel()
    {
        static::assertFalse(
            CloudVisionVoterHelper::vote('sex')
        );
    }

    public function testRightLabel()
    {
        static::assertTrue(
            CloudVisionVoterHelper::vote('troll')
        );
    }
}
