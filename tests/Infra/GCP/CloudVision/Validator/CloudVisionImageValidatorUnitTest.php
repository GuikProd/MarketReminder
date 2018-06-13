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

namespace App\Tests\Infra\GCP\CloudVision\Validator;

use App\Infra\GCP\CloudVision\Interfaces\CloudVisionAnalyserHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionDescriberHelperInterface;
use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;
use App\Infra\GCP\CloudVision\Validator\CloudVisionImageValidator;
use App\Infra\GCP\CloudVision\Validator\Interfaces\CloudVisionImageValidatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudVisionImageValidatorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionImageValidatorUnitTest extends TestCase
{
    /**
     * @var CloudVisionAnalyserHelperInterface
     */
    private $cloudVisionAnalyserHelper;

    /**
     * @var CloudVisionDescriberHelperInterface
     */
    private $cloudVisionDescriber;

    /**
     * @var CloudVisionVoterHelperInterface
     */
    private $cloudVisionVoter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudVisionAnalyserHelper = $this->createMock(CloudVisionAnalyserHelperInterface::class);
        $this->cloudVisionDescriber = $this->createMock(CloudVisionDescriberHelperInterface::class);
        $this->cloudVisionVoter = $this->createMock(CloudVisionVoterHelperInterface::class);
    }

    public function testItImplements()
    {
        $cloudVisionImageValidator = new CloudVisionImageValidator(
            $this->cloudVisionAnalyserHelper,
            $this->cloudVisionDescriber,
            $this->cloudVisionVoter
        );

        static::assertInstanceOf(
            CloudVisionImageValidatorInterface::class,
            $cloudVisionImageValidator
        );
    }
}
