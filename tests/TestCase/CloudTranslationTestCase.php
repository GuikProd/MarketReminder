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

namespace App\Tests\TestCase;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationBackupWriter;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationWriter;
use App\Infra\GCP\CloudTranslation\Helper\Factory\CloudTranslationFactory;
use App\Infra\GCP\CloudTranslation\Helper\Factory\Interfaces\CloudTranslationFactoryInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationBackupWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWriterInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\CloudTranslationValidator;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationTestCase.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
abstract class CloudTranslationTestCase extends TestCase
{
    use ConnectorTraitTestCase;

    /**
     * @var CloudTranslationFactoryInterface
     */
    protected $cloudTranslationFactory;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    protected $cloudTranslationRepository;

    /**
     * @var CloudTranslationValidatorInterface
     */
    protected $cloudTranslationValidator;

    /**
     * @var CloudTranslationBackupWriterInterface
     */
    protected $cloudTranslationBackUpWriter;

    /**
     * @var CloudTranslationWriterInterface
     */
    protected $cloudTranslationWriter;

    public function createCloudTranslationWriter()
    {
        $this->createCloudTranslationFactory();
        $this->createCloudTranslationValidator();

        $this->cloudTranslationWriter = new CloudTranslationWriter(
            $this->connector,
            $this->cloudTranslationFactory,
            $this->cloudTranslationValidator
        );
    }

    public function createCloudTranslationBackUpWriter()
    {
        $this->createCloudTranslationFactory();
        $this->createCloudTranslationValidator();

        $this->cloudTranslationBackUpWriter = new CloudTranslationBackupWriter(
            $this->backUpConnector
        );
    }

    private function createCloudTranslationFactory()
    {
        $this->cloudTranslationFactory = new CloudTranslationFactory();
    }

    private function createCloudTranslationValidator()
    {
        $this->cloudTranslationValidator = new CloudTranslationValidator();
    }
}
