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

namespace App\Infra\GCP\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class GCPConfiguration.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class GCPConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gcp');

        $rootNode
            ->children()
                ->scalarNode('credentials_filename')->end()
                ->scalarNode('credentials_folder')->end()
                ->arrayNode('storage')
                    ->children()
                        ->scalarNode('activated')->isRequired()->end()
                        ->scalarNode('bucket_name')->end()
                        ->scalarNode('bucket_public_url')->end()
                    ->end()
                ->end()
                ->arrayNode('translation')
                    ->children()
                        ->scalarNode('activated')->isRequired()->end()
                        ->enumNode('storage_engine')
                            ->values([
                                'redis',
                                'filesystem'
                            ])
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('vision')
                    ->children()
                        ->scalarNode('activated')->isRequired()->end()
                        ->arrayNode('forbidden_labels')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
