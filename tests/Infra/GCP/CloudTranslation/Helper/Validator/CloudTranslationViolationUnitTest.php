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
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationViolationInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationViolationUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationViolationUnitTest extends TestCase
{
    /**
     * @dataProvider provideViolationData
     *
     * @param string $message
     * @param string $context
     */
    public function testItImplements(string $message, string $context)
    {
        $cloudTranslationViolation = new CloudTranslationViolation(
            $message,
            $context
        );

        static::assertInstanceOf(
            CloudTranslationViolationInterface::class,
            $cloudTranslationViolation
        );
    }

    /**
     * @dataProvider provideViolationData
     *
     * @param string $message
     * @param string $context
     */
    public function testItReturnData(string $message, string $context)
    {
        $cloudTranslationViolation = new CloudTranslationViolation(
            $message,
            $context
        );

        static::assertSame($message, $cloudTranslationViolation->getMessage());
        static::assertSame($context, $cloudTranslationViolation->getContext());
    }

    /**
     * @return \Generator
     */
    public function provideViolationData()
    {
        yield array('This value is already in cache !', 'Cache error');
        yield array('This value is already valid !', 'Validity error');
    }
}
