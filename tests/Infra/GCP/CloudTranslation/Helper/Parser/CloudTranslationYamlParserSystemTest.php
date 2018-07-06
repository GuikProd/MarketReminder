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
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationYamlParserSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * @group Blackfire
 *
 * @requires extension blackfire
 */
class CloudTranslationYamlParserSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationYamlParserInterface|null
     */
    private $parser = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->parser = new CloudTranslationYamlParser();
    }

    public function testFileIsParsed()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 420kB', 'CloudTranslationYamlParser memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->parser->parseYaml(__DIR__.'./../../../../../_assets', 'test');
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->parser = null;
    }
}
