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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper\Parser;

use App\Infra\GCP\CloudTranslation\Helper\Parser\CloudTranslationYamlParser;
use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationYamlParserUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationYamlParserUnitTest extends TestCase
{
    public function testItImplements()
    {
        $parser = new CloudTranslationYamlParser();

        static::assertInstanceOf(
            CloudTranslationYamlParserInterface::class,
            $parser
        );
    }

    public function testParseFile()
    {
        $parser = new CloudTranslationYamlParser();

        $parser->parseYaml(__DIR__.'/../../../../../_assets', 'test');

        static::assertNotNull($parser->getKeys());
        static::assertNotNull($parser->getValues());
    }
}
