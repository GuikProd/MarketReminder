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

use App\Infra\GCP\Bridge\CloudStorageBridge;
use App\Infra\GCP\Bridge\CloudTranslationBridge;
use App\Infra\GCP\Bridge\CloudVisionBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Infra\GCP\CloudStorage\CloudStorageCleanerHelper;
use App\Infra\GCP\CloudStorage\CloudStoragePersisterHelper;
use App\Infra\GCP\CloudStorage\CloudStorageRetrieverHelper;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStorageCleanerHelperInterface;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStoragePersisterHelperInterface;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStorageRetrieverHelperInterface;
use App\Infra\GCP\CloudTranslation\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\FileSystemConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationWriterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class GCPExtension.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
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
            if (!$container->hasDefinition(new Reference(CloudStorageBridgeInterface::class))) {
                $container->register(CloudStorageBridgeInterface::class, CloudStorageBridge::class)
                          ->addArgument($container->getParameter('cloud.storage_credentials.filename'))
                          ->addArgument($container->getParameter('cloud.storage_credentials'))
                          ->setPublic(false)
                          ->addTag('gcp.storage_bridge');
            }

            if (!$container->hasDefinition(CloudStoragePersisterHelperInterface::class)) {
                $container->register(CloudStoragePersisterHelperInterface::class, CloudStoragePersisterHelper::class)
                          ->addArgument($container->getDefinition(CloudStorageBridgeInterface::class))
                          ->setPublic(false)
                          ->addTag('gcp.storage');
            }

            if (!$container->hasDefinition(CloudStorageRetrieverHelperInterface::class)) {
                $container->register(CloudStorageRetrieverHelperInterface::class, CloudStorageRetrieverHelper::class)
                          ->addArgument($container->getDefinition(CloudStorageBridgeInterface::class))
                          ->setPublic(false)
                          ->addTag('gcp.storage');
            }

            if (!$container->hasDefinition(CloudStorageCleanerHelperInterface::class)) {
                $container->register(CloudStorageCleanerHelperInterface::class, CloudStorageCleanerHelper::class)
                          ->addArgument($container->getDefinition(CloudStorageBridgeInterface::class))
                          ->setPublic(false)
                          ->addTag('gcp.storage');
            }
        }

        // Translation
        if ($config['translation']['activated']) {
            if (!$container->hasDefinition(new Reference(CloudTranslationBridgeInterface::class))) {
                $container->register(CloudTranslationBridgeInterface::class, CloudTranslationBridge::class)
                          ->addArgument($container->getParameter('cloud.translation_credentials.filename'))
                          ->addArgument($container->getParameter('cloud.translation_credentials'))
                          ->setPublic(false)
                          ->addTag('gcp.translation_bridge');
            }

            // Storage engine
            if ('redis' === $config['translation']['storage_engine']) {
                if (!$container->hasDefinition(RedisConnectorInterface::class)) {
                    $container->register(RedisConnectorInterface::class, RedisConnector::class)
                              ->addArgument(getenv('REDIS_URL'))
                              ->addArgument(getenv('APP_ENV'))
                              ->addTag('gcp.translation_connector');
                    $container->register(ConnectorInterface::class, ConnectorInterface::class);
                    $container->setAlias(ConnectorInterface::class, RedisConnectorInterface::class);
                }

            } elseif ('filesystem' === $config['translation']['storage_engine']) {
                if (!$container->hasDefinition(FileSystemConnectorInterface::class)) {
                    $container->register(FileSystemConnectorInterface::class, FileSystemConnector::class)
                              ->addArgument(getenv('APP_ENV'))
                              ->addTag('gcp.translation_connector');
                    $container->register(ConnectorInterface::class, ConnectorInterface::class);
                    $container->setAlias(ConnectorInterface::class, FileSystemConnectorInterface::class);
                }
            }

            // Backup engine
            if ('filesystem' === $config['translation']['backup_engine']) {
                if (!$container->hasDefinition(FileSystemConnectorInterface::class)) {
                    $container->register(FileSystemConnectorInterface::class, FileSystemConnector::class)
                              ->addArgument(getenv('REDIS_URL'))
                              ->addArgument(getenv('APP_ENV'))
                              ->addTag('gcp.translation_connector.backup');

                    if (!$container->hasDefinition(CloudTranslationBackupWriterInterface::class)) {
                        $container->register(CloudTranslationBackupWriterInterface::class, CloudTranslationBackupWriter::class)
                                  ->addArgument($container->getDefinition(FileSystemConnectorInterface::class))
                                  ->addTag('gcp.translation_backup');
                    }
                }

                $container->getDefinition(FileSystemConnectorInterface::class)
                          ->addTag('gcp.translation_connector.backup');

            } elseif ('redis' === $config['translation']['backup_engine']) {
                if (!$container->hasDefinition(RedisConnectorInterface::class)) {
                    $container->register(RedisConnectorInterface::class, RedisConnector::class)
                              ->addArgument(getenv('REDIS_URL'))
                              ->addArgument(getenv('APP_ENV'))
                              ->addTag('gcp.translation_connector.backup');

                    if (!$container->hasDefinition(CloudTranslationBackupWriter::class)) {
                        $container->register(CloudTranslationBackupWriter::class, CloudTranslationBackupWriter::class)
                                  ->addArgument($container->getDefinition(RedisConnectorInterface::class))
                                  ->addTag('gcp.translation_backup');
                        $container->register(CloudTranslationBackupWriterInterface::class, CloudTranslationBackupWriterInterface::class);
                        $container->setAlias(CloudTranslationBackupWriterInterface::class, CloudTranslationBackupWriter::class);
                    }
                }

                $container->getDefinition(RedisConnectorInterface::class)
                          ->addTag('gcp.translation_connector.backup');
            }

            if (!$container->hasDefinition(CloudTranslationWriter::class)) {
                $container->register(CloudTranslationWriter::class, CloudTranslationWriter::class)
                          ->addArgument($container->findTaggedServiceIds('gcp.translation_backup'))
                          ->addArgument($container->findTaggedServiceIds('gcp.translation_connector'))
                          ->setPublic(false)
                          ->addTag('gcp.translation');
                $container->setAlias(CloudTranslationWriterInterface::class, CloudTranslationWriter::class);
            }

            if (!$container->hasDefinition(CloudTranslationRepositoryInterface::class)) {
                $container->register(CloudTranslationRepositoryInterface::class, CloudTranslationRepository::class)
                          ->addArgument($container->findTaggedServiceIds('gcp.translation_connector'))
                          ->addArgument($container->findTaggedServiceIds('gcp.translation_connector.backup'))
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
