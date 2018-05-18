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

namespace App\Tests\Application\DependencyInjection;

use App\Application\DependencyInjection\GCPConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class GCPConfigurationUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class GCPConfigurationUnitTest extends TestCase
{
    public function testItImplements()
    {
        $configuration = new GCPConfiguration();

        static::assertInstanceOf(
            ConfigurationInterface::class,
            $configuration
        );
    }

    public function testItResolveTreeBuilder()
    {
        $configuration = new GCPConfiguration();

        static::assertInstanceOf(
            TreeBuilder::class,
            $configuration->getConfigTreeBuilder()
        );
    }
}
