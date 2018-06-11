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

namespace App\Tests\Infra\GCP\CloudStorage\Helper;

use App\Infra\GCP\CloudStorage\Helper\CloudStorageWriterHelper;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use App\Tests\TestCase\CloudStorageTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;

/**
 * Class CloudStorageWriterSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudStorageWriterSystemTest extends CloudStorageTestCase
{
    use TestCaseTrait;

    /**
     * @var CloudStorageWriterHelperInterface
     */
    private $cloudStorageWriter;

    protected function setUp()
    {
        $this->createCloudStorageBridge();

        $this->cloudStorageWriter = new CloudStorageWriterHelper($this->cloudStorageBridge);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItStoreObject()
    {
        $configuration = new Configuration();

        $this->assertBlackfire($configuration, function () {
            $this->cloudStorageWriter->persist('', '');
        });
    }
}
