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

namespace App\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class RedisConfiguration.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RedisConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redis');

        $rootNode
            ->children()
                ->scalarNode('env')->isRequired()->end()
                ->scalarNode('dsn')->isRequired()->end()
                ->scalarNode('namespace')->isRequired()->end()
                ->scalarNode('client')->isRequired()->end()
                ->arrayNode('translation')
                    ->children()
                        ->scalarNode('activated')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
