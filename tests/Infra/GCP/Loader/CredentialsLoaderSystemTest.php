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

namespace App\Tests\Infra\GCP\Loader;

use App\Infra\GCP\Loader\CredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CredentialsLoaderSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CredentialsLoaderSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var LoaderInterface
     */
    private $loader = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->loader = new CredentialsLoader();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItRefuseToLoadFile()
    {
        static::expectException(\InvalidArgumentException::class);

        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 20kB', 'Loader error memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->loader->loadJson('toto.json', __DIR__.'/../../../_credentials');
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItLoadFile()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 25kB', 'Loader success memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->loader->loadJson('credentials.json', __DIR__.'/../../../_credentials');
        });
    }
}
