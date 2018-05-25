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

namespace App\Infra\GCP\DependencyInjection;

use App\Infra\GCP\Bridge\CloudStorageBridge;
use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\Bridge\CloudVisionBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Connector\ApcuConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ApcuConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class GCPExtension.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class GCPExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new GCPConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        // Storage
        if ($config['storage']['activated']) {
            if (!$container->hasDefinition(new Reference(CloudStorageBridge::class))) {
                $container->register(CloudStorageBridge::class, CloudStorageBridge::class)
                          ->addArgument($container->getParameter('cloud.storage_credentials.filename'))
                          ->addArgument($container->getParameter('cloud.storage_credentials'))
                          ->setPublic(false)
                          ->addTag('gcp.storage');
                $container->setAlias(CloudStorageBridgeInterface::class, CloudStorageBridge::class);
            }
        }

        // Translation
        if ($config['translation']['activated']) {
            if (!$container->hasDefinition(new Reference(CloudTranslationBridge::class))) {
                $container->register(CloudTranslationBridge::class, CloudTranslationBridge::class)
                          ->addArgument($container->getParameter('cloud.translation_credentials.filename'))
                          ->addArgument($container->getParameter('cloud.translation_credentials'))
                          ->setPublic(false)
                          ->addTag('gcp.translation');
                $container->setAlias(CloudTranslationBridgeInterface::class, CloudTranslationBridge::class);
            }

            // Storage engine
            if ('apcu' === $config['translation']['storage_engine']) {
                if (!$container->hasDefinition(ApcuConnector::class)) {
                    $container->register(ApcuConnector::class, ApcuConnector::class)
                        ->addArgument(getenv('APP_ENV'))
                        ->addTag('gcp.translation_connector');
                    $container->setAlias(ApcuConnectorInterface::class, ApcuConnector::class);
                    $container->setAlias(ConnectorInterface::class, ApcuConnector::class);
                }

                if (!$container->hasDefinition(CloudTranslationRepository::class)) {
                    $container->register(CloudTranslationRepository::class, CloudTranslationRepository::class)
                              ->addArgument($container->getDefinition(ApcuConnector::class))
                              ->setPublic(false)
                              ->addTag('gcp.translation');
                    $container->setAlias(CloudTranslationRepositoryInterface::class, CloudTranslationRepository::class);
                }

            } elseif ('redis' === $config['translation']['storage_engine']) {
                if (!$container->hasDefinition(RedisConnector::class)) {
                    $container->register(RedisConnector::class, RedisConnector::class)
                              ->addArgument(getenv('REDIS_URL'))
                              ->addArgument(getenv('APP_ENV'))
                              ->addTag('gcp.translation_connector');
                    $container->setAlias(RedisConnectorInterface::class, RedisConnector::class);
                    $container->setAlias(ConnectorInterface::class, RedisConnector::class);
                }

                if (!$container->hasDefinition(CloudTranslationRepository::class)) {
                    $container->register(CloudTranslationRepository::class, CloudTranslationRepository::class)
                              ->addArgument($container->getDefinition(RedisConnector::class))
                              ->setPublic(false)
                              ->addTag('gcp.translation');
                }
            }

            // Vision
            if ($config['vision']['activated']) {
                if (!$container->hasDefinition(new Reference(CloudVisionBridge::class))) {
                    $container->register(CloudVisionBridge::class, CloudVisionBridge::class)
                              ->addArgument($container->getParameter('cloud.vision_credentials.filename'))
                              ->addArgument($container->getParameter('cloud.vision_credentials'))
                              ->setPublic(false)
                              ->addTag('gcp.vision');
                    $container->setAlias(CloudVisionBridgeInterface::class, CloudVisionBridge::class);
                }
            }
        }
    }
}
