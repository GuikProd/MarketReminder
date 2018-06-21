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

namespace App\Infra\GCP\CloudTranslation\Connector\BackUp;

use App\Infra\GCP\CloudTranslation\Connector\BackUp\Interfaces\BackUpConnectorInterface;
use App\Infra\GCP\CloudTranslation\Connector\BackUp\Interfaces\FileSystemConnectorInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class FileSystemConnector.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class FileSystemConnector implements FileSystemConnectorInterface, BackUpConnectorInterface
{
    /**
     * @var bool
     */
    private $isActivated = false;

    /**
     * @var string
     */
    private $namespace;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getBackUpAdapter(): CacheItemPoolInterface
    {
        if (!$this->isActivated) {
            throw new \LogicException(
                sprintf('The backup connector should be enabled in order to use it !')
            );
        }

        return new FilesystemAdapter($this->namespace);
    }

    /**
     * {@inheritdoc}
     */
    public function activate(bool $isActivated): void
    {
        $this->isActivated = $isActivated;
    }
}
