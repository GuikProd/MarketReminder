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

namespace App\Tests\Infra\GCP\CloudVision;

use App\Infra\GCP\CloudVision\CloudVisionVoterHelper;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudVisionVoterHelperUnitTest;.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionVoterHelperUnitTest extends TestCase
{
    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudVisionVoter = new CloudVisionVoterHelper(['sex', 'drugs', 'gun', 'money']);
    }

    public function testWrongLabel()
    {
        $this->cloudVisionVoter->vote('sex');

        static::assertFalse($this->cloudVisionVoter->isLabelAuthorized());
    }

    public function testRightLabel()
    {
        $this->cloudVisionVoter->vote('troll');

        static::assertTrue($this->cloudVisionVoter->isLabelAuthorized());
    }
}
