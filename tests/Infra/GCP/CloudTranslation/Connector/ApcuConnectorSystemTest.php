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

namespace App\Tests\Infra\GCP\CloudTranslation\Connector;

use App\Infra\GCP\CloudTranslation\Connector\ApcuConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class ApcuConnectorSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ApcuConnectorSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var ApcuConnectorInterface
     */
    private $apcuConnector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->apcuConnector = new ApcuConnector('test');
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testConnectorCall()
    {
        $configuration = new Configuration();
        $configuration->setMetadata('skip_timeline', 'false');
        $configuration->assert('main.peak_memory < 260kB', 'APCu connector call memory usage');

        $this->assertBlackfire($configuration, function() {
            $this->apcuConnector->getAdapter();
        });
    }
}
