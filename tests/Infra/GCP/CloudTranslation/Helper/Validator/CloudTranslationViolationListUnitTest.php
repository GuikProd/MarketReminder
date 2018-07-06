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
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationViolationListUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationViolationListUnitTest extends TestCase
{
    public function testItImplements()
    {
        $cloudTranslationViolationList = new CloudTranslationViolationList();

        static::assertInstanceOf(
            CloudTranslationViolationListInterface::class,
            $cloudTranslationViolationList
        );
    }

    /**
     * @dataProvider provideViolations
     *
     * @param array $violations
     */
    public function testItReturnViolations(array $violations)
    {
        $cloudTranslationViolationList = new CloudTranslationViolationList($violations);

        static::assertGreaterThan(0, $cloudTranslationViolationList->getIterator()->count());
    }

    public function provideViolations()
    {
        yield array([
            new CloudTranslationViolation('', ''),
            new CloudTranslationViolation('', '')
        ]);
    }
}
