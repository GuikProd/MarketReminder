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
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\FileSystemConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
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
            if (!$container->hasDefinition(new Reference(CloudStorageBridge::class))) {
                $container->register(CloudStorageBridge::class, CloudStorageBridge::class)
                          ->addArgument($container->getParameter('cloud.storage_credentials.filename'))
                          ->addArgument($container->getParameter('cloud.storage_credentials'))
                          ->setPublic(false)
                          ->addTag('gcp.storage_bridge');
                $container->register(CloudStorageBridgeInterface::class, CloudStorageBridgeInterface::class);
                $container->setAlias(CloudStorageBridgeInterface::class, CloudStorageBridge::class);
            }

            if (!$container->hasDefinition(CloudStoragePersisterHelper::class)) {
                $container->register(CloudStoragePersisterHelper::class, CloudStoragePersisterHelper::class)
                          ->addArgument($container->findTaggedServiceIds('gcp.storage_bridge'))
                          ->setPublic(false);
                $container->register(CloudStoragePersisterHelperInterface::class, CloudStoragePersisterHelperInterface::class);
                $container->setAlias(CloudStoragePersisterHelperInterface::class, CloudStoragePersisterHelper::class);
            }

            if (!$container->hasDefinition(CloudStorageRetrieverHelper::class)) {
                $container->register(CloudStorageRetrieverHelper::class, CloudStorageRetrieverHelper::class)
                          ->addArgument($container->findTaggedServiceIds('gcp.storage_bridge'))
                          ->setPublic(false);
                $container->register(CloudStorageRetrieverHelperInterface::class, CloudStorageRetrieverHelperInterface::class);
                $container->register(CloudStorageRetrieverHelperInterface::class, CloudStorageRetrieverHelper::class);
            }

            if (!$container->hasDefinition(CloudStorageCleanerHelper::class)) {
                $container->register(CloudStorageCleanerHelper::class, CloudStorageCleanerHelper::class)
                          ->addArgument($container->findTaggedServiceIds('gcp.storage_bridge'))
                          ->setPublic(false);
                $container->register(CloudStorageCleanerHelperInterface::class, CloudStorageCleanerHelperInterface::class);
                $container->register(CloudStorageCleanerHelperInterface::class, CloudStorageCleanerHelper::class);
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
            if ('redis' === $config['translation']['storage_engine']) {
                if (!$container->hasDefinition(RedisConnector::class)) {
                    $container->register(RedisConnector::class, RedisConnector::class)
                              ->addArgument(getenv('REDIS_URL'))
                              ->addArgument(getenv('APP_ENV'))
                              ->addTag('gcp.translation_connector');
                    $container->setAlias(RedisConnectorInterface::class, RedisConnector::class);
                    $container->register(ConnectorInterface::class, ConnectorInterface::class);
                    $container->setAlias(ConnectorInterface::class, RedisConnector::class);
                }

                if (!$container->hasDefinition(CloudTranslationRepository::class)) {
                    $container->register(CloudTranslationRepository::class, CloudTranslationRepository::class)
                              ->addArgument($container->getDefinition(RedisConnector::class))
                              ->setPublic(false)
                              ->addTag('gcp.translation_connector');
                }
            } elseif ('filesystem' === $config['translation']['storage_engine']) {
                if (!$container->hasDefinition(FileSystemConnector::class)) {
                    $container->register(FileSystemConnector::class, FileSystemConnector::class)
                              ->addArgument(getenv('APP_ENV'))
                              ->addTag('gcp.translation_connector');
                    $container->setAlias(FileSystemConnectorInterface::class, FileSystemConnector::class);
                    $container->register(ConnectorInterface::class, ConnectorInterface::class);
                    $container->setAlias(ConnectorInterface::class, FileSystemConnector::class);
                }

                if (!$container->hasDefinition(CloudTranslationRepository::class)) {
                    $container->register(CloudTranslationRepository::class, CloudTranslationRepository::class)
                              ->addArgument($container->getDefinition(FileSystemConnector::class))
                              ->setPublic(false)
                              ->addTag('gcp.translation_connector');
                }
            }

            // Backup engine
            if ('filesystem' === $config['translation']['backup_engine']) {
                if (!$container->hasDefinition(FileSystemConnector::class)) {
                    $container->register(FileSystemConnector::class, FileSystemConnector::class)
                              ->addArgument(getenv('REDIS_URL'))
                              ->addArgument(getenv('APP_ENV'))
                              ->addMethodCall('setBackup', [true])
                              ->addTag('gcp.translation_connector.backup');
                    $container->setAlias(FileSystemConnectorInterface::class, FileSystemConnector::class);
                    $container->setAlias(BackupConnectorInterface::class, FileSystemConnector::class);

                    if (!$container->hasDefinition(CloudTranslationBackupWriter::class)) {
                        $container->register(CloudTranslationBackupWriter::class, CloudTranslationBackupWriter::class)
                                  ->addArgument($container->getDefinition(FileSystemConnector::class))
                                  ->addTag('gcp.translation_backup');
                        $container->register(CloudTranslationBackupWriterInterface::class, CloudTranslationBackupWriterInterface::class);
                        $container->setAlias(CloudTranslationBackupWriterInterface::class, CloudTranslationBackupWriter::class);
                    }
                }
            } elseif ('redis' === $config['translation']['backup_engine']) {
                if (!$container->hasDefinition(RedisConnector::class)) {
                    $container->register(RedisConnector::class, RedisConnector::class)
                              ->addArgument(getenv('REDIS_URL'))
                              ->addArgument(getenv('APP_ENV'))
                              ->addMethodCall('setBackup', [true])
                              ->addTag('gcp.translation_connector.backup');
                    $container->setAlias(FileSystemConnectorInterface::class, RedisConnector::class);
                    $container->register(BackupConnectorInterface::class, BackupConnectorInterface::class);
                    $container->setAlias(BackupConnectorInterface::class, RedisConnector::class);

                    if (!$container->hasDefinition(CloudTranslationBackupWriter::class)) {
                        $container->register(CloudTranslationBackupWriter::class, CloudTranslationBackupWriter::class)
                                  ->addArgument($container->getDefinition(RedisConnector::class))
                                  ->addTag('gcp.translation_backup');
                        $container->register(CloudTranslationBackupWriterInterface::class, CloudTranslationBackupWriterInterface::class);
                        $container->setAlias(CloudTranslationBackupWriterInterface::class, CloudTranslationBackupWriter::class);
                    }
                }
            }

            if (!$container->hasDefinition(CloudTranslationWriter::class)) {
                $container->register(CloudTranslationWriter::class, CloudTranslationWriter::class)
                          ->addArgument($container->findTaggedServiceIds('gcp.translation_backup'))
                          ->addArgument($container->findTaggedServiceIds('gcp.translation_connector'))
                          ->setPublic(false)
                          ->addTag('gcp.translation');
                $container->setAlias(CloudTranslationWriterInterface::class, CloudTranslationWriter::class);
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
