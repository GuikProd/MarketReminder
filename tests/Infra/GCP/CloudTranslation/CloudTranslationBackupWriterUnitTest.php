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

namespace App\Tests\Infra\GCP\CloudTranslation;

use App\Infra\GCP\CloudTranslation\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\Connector\Interfaces\BackupConnectorInterface;
use App\Infra\GCP\CloudTranslation\Interfaces\CloudTranslationBackupWriterInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationBackupWriterUnitTest.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationBackupWriterUnitTest extends TestCase
{
    /**
     * @var BackupConnectorInterface
     */
    private $fileSystemBackupConnector;

    /**
     * @var BackupConnectorInterface
     */
    private $redisBackupConnector;

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        $this->fileSystemBackupConnector = $this->createMock(BackupConnectorInterface::class);
        $this->redisBackupConnector = $this->createMock(BackupConnectorInterface::class);
    }

    public function testItImplements()
    {
        $cloudTranslationBackupWriter = new CloudTranslationBackupWriter($this->fileSystemBackupConnector);

        static::assertInstanceOf(
            CloudTranslationBackupWriterInterface::class,
            $cloudTranslationBackupWriter
        );
    }
}
