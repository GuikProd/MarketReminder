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
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudVisionVoterHelperSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionVoterHelperSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudVisionVoter = new CloudVisionVoterHelper(['sex', 'drugs', 'money', 'gun']);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItRefuseLabel()
    {
        $configuration = new Configuration();
        $configuration->assert(
            'main.peak_memory < 4kB',
            'CloudVisionVoter wrong label memory usage'
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudVisionVoter->vote('gun');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItAcceptLabel()
    {
        $configuration = new Configuration();
        $configuration->assert(
            'main.peak_memory < 4kB',
            'CloudVisionVoter wrong label memory usage'
        );

        $this->assertBlackfire($configuration, function () {
            $this->cloudVisionVoter->vote('troll');
        });
    }
}
