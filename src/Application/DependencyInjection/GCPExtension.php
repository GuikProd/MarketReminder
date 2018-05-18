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

namespace App\Application\DependencyInjection;

use App\Infra\GCP\Bridge\CloudStorageBridge;
use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\Bridge\CloudVisionBridge;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Class GCPExtension.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class GCPExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new GCPConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        if (!$container->hasDefinition('App\Infra\GCP\Bridge\CloudStorageBridge') && $config['vision']['activated']) {
            $container->register('App\Infra\GCP\Bridge\CloudStorageBridge', CloudStorageBridge::class)
                      ->addArgument($config['storage']['credentials_filename'])
                      ->addArgument($config['storage']['credentials_filename'])
                      ->setPublic(false)
                      ->addTag('gcp.storage');
        }

        if (!$container->hasDefinition('App\Infra\GCP\Bridge\CloudTranslationBridge') && $config['translation']['activated']) {
            $container->register(
                'App\Infra\GCP\Bridge\CloudTranslationBridge',
                new CloudTranslationBridge(
                    $config['translation']['credentials_filename'],
                    $config['translation']['credentials_folder']
                )
            )->setPublic(false)->addTag('gcp.translation');
        }

        if (!$container->hasDefinition('App\Infra\GCP\Bridge\CloudVisionBridge') && $config['vision']['activated']) {
            $container->register(
                'App\Infra\GCP\Bridge\CloudVisionBridge',
                new CloudVisionBridge(
                    $config['vision']['credentials_filename'],
                    $config['vision']['credentials_folder']
                )
            )->setPublic(false)->addTag('gcp.vision');
        }
    }
}
