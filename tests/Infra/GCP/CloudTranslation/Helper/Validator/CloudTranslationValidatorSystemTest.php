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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper\Validator;

use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslation;
use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Helper\Validator\CloudTranslationValidator;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationValidatorSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationValidatorSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationValidatorInterface
     */
    private $cloudTranslationValidator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationValidator = new CloudTranslationValidator();
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testViolationsAreTriggered()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 60kB', 'CloudTranslationValidator violations memory usage');
        $configuration->assert('main.network_in == 0B', 'CloudTranslationValidator violations network in');
        $configuration->assert('main.network_out == 0B', 'CloudTranslationValidator violations network out');
        $configuration->assert('metrics.http.requests.count == 0', 'CloudTranslationValidator violations HTTP Requests');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationValidator->validate(
                new CloudTranslation('messages.fr.yaml', 'messages', [
                    new CloudTranslationItem([
                    '_locale' => 'fr',
                    'tag' => 'Hello',
                    'channel' => 'messages',
                    'key' => 'hello.home',
                    'value' => 'Hello !'])]
                ), ['home.text' => 'Hello World !']
            );
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testContentIsValid()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 6kB', 'CloudTranslationValidator no violations memory usage');
        $configuration->assert('main.network_in == 0B', 'CloudTranslationValidator no violations network in');
        $configuration->assert('main.network_out == 0B', 'CloudTranslationValidator no violations network out');
        $configuration->assert('metrics.http.requests.count == 0', 'CloudTranslationValidator no violations HTTP Requests');

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationValidator->validate(
                new CloudTranslation('messages.fr.yaml', 'messages', [
                        new CloudTranslationItem([
                            '_locale' => 'fr',
                            'tag' => 'Hello',
                            'channel' => 'messages',
                            'key' => 'home.text',
                            'value' => 'Hello World !'])]
                ), ['home.text' => 'Hello World !']
            );
        });
    }
}
