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

namespace App\Tests\Application\Validator;

use App\Application\Validator\ImageContentValidator;
use App\Application\Validator\Interfaces\ImageContentValidatorInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudVision\Validator\Interfaces\CloudVisionImageValidatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ImageContentValidatorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ImageContentValidatorUnitTest extends TestCase
{
    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * @var CloudVisionImageValidatorInterface
     */
    private $cloudVisionImageValidator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->cloudVisionImageValidator = $this->createMock(CloudVisionImageValidatorInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
    }

    public function testItExtendsAndImplements()
    {
        $imageContentValidator = new ImageContentValidator(
            $this->cloudTranslationRepository,
            $this->cloudVisionImageValidator,
            $this->requestStack
        );

        static::assertInstanceOf(ConstraintValidator::class, $imageContentValidator);
        static::assertInstanceOf(ImageContentValidatorInterface::class, $imageContentValidator);
    }
}
