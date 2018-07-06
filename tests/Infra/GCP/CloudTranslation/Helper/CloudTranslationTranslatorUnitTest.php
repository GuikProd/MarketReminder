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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper;

use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\CloudTranslation\Helper\CloudTranslationTranslator;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationTranslatorInterface;
use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationTranslatorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationTranslatorUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $translationsFolder;

    /**
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationClient;

    /**
     * @var CloudTranslationYamlParserInterface
     */
    private $cloudTranslationYamlParser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->translationsFolder = '/tmp/translations';
        $this->cloudTranslationClient = $this->createMock(CloudTranslationClientInterface::class);
        $this->cloudTranslationYamlParser = $this->createMock(CloudTranslationYamlParserInterface::class);
    }

    public function testItImplements()
    {
        $cloudTranslationTranslator = new CloudTranslationTranslator(
            $this->translationsFolder,
            $this->cloudTranslationClient,
            $this->cloudTranslationYamlParser
        );

        static::assertInstanceOf(
            CloudTranslationTranslatorInterface::class,
            $cloudTranslationTranslator
        );
    }
}
