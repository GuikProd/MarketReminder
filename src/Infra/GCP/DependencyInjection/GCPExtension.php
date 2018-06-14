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

use App\Infra\GCP\Bridge\CloudVisionBridge;
use App\Infra\GCP\Bridge\Interfaces\CloudVisionBridgeInterface;
use App\Infra\GCP\CloudStorage\Bridge\CloudStorageBridge;
use App\Infra\GCP\CloudStorage\Bridge\Interfaces\CloudStorageBridgeInterface;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageCleanerHelper;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageRetrieverHelper;
use App\Infra\GCP\CloudStorage\Helper\CloudStorageWriterHelper;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageCleanerHelperInterface;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageRetrieverHelperInterface;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use App\Infra\GCP\CloudTranslation\Connector\FileSystemConnector;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\ConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\FileSystemConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\RedisConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\RedisConnector;
use App\Infra\GCP\CloudTranslation\Domain\Repository\CloudTranslationRepository;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudVision\CloudVisionAnalyserHelper;
use App\Infra\GCP\CloudVision\CloudVisionDescriberHelper;
use App\Infra\GCP\CloudVision\CloudVisionVoterHelper;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;
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

            if (!$container->hasDefinition(CloudStorageWriterHelperInterface::class)) {
                $container->register(CloudStorageWriterHelperInterface::class, CloudStorageWriterHelper::class)
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
                          ->addArgument($config['translation']['credentials_folder'])
                          ->addArgument($config['translation']['credentials_filename'])
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
            if (!$container->hasDefinition(CloudVisionBridgeInterface::class)) {
                $container->register(CloudVisionBridgeInterface::class, CloudVisionBridge::class)
                          ->addArgument($container->getParameter('cloud.vision_credentials.filename'))
                          ->addArgument($container->getParameter('cloud.vision_credentials'))
                          ->setPublic(false)
                          ->addTag('gcp.vision_bridge');
            }

            if (!$container->hasDefinition(CloudVisionAnalyserHelperInterface::class)) {
                $container->register(CloudVisionAnalyserHelperInterface::class, CloudVisionAnalyserHelper::class)
                          ->addArgument($container->getDefinition(CloudVisionBridgeInterface::class))
                          ->setPublic(false)
                          ->addTag('gcp.vision_helper');
            }

            if (!$container->hasDefinition(CloudVisionDescriberHelperInterface::class)) {
                $container->register(CloudVisionDescriberHelperInterface::class, CloudVisionDescriberHelper::class)
                          ->addArgument($container->getDefinition(CloudVisionBridgeInterface::class))
                          ->setPublic(false)
                          ->addTag('gcp.vision_helper');
            }

            if (!$container->hasDefinition(CloudVisionVoterHelperInterface::class)) {
                $container->register(CloudVisionVoterHelperInterface::class, CloudVisionVoterHelper::class)
                          ->addArgument($config['vision']['forbidden_labels'])
                          ->setPublic(false)
                          ->addTag('gcp.vision_helper');
            }
        }
    }
}
