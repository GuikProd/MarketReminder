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

use App\Infra\GCP\CloudTranslation\Helper\Validator\CloudTranslationViolation;
use App\Infra\GCP\CloudTranslation\Helper\Validator\CloudTranslationViolationList;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationViolationListInterface;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationViolationListSystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CloudTranslationViolationListSystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var CloudTranslationViolationListInterface
     */
    private $cloudTranslationViolationList;

    /**
     * @var array
     */
    private $violations = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->violations[] = new CloudTranslationViolation('', '');
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItAcceptViolations()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 10kB', 'CloudTranslationViolationList violations memory usage');

        $this->cloudTranslationViolationList = new CloudTranslationViolationList($this->violations);

        $this->assertBlackfire($configuration, function () {
            $this->cloudTranslationViolationList->getIterator();
        });
    }
}
