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

namespace App\Application\Validator;

use App\Application\Validator\Interfaces\ImageContentValidatorInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudVision\Validator\Interfaces\CloudVisionImageValidatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ImageContentValidator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ImageContentValidator extends ConstraintValidator implements ImageContentValidatorInterface
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
    public function __construct(
        CloudTranslationRepositoryInterface $cloudTranslationRepository,
        CloudVisionImageValidatorInterface $cloudVisionImageValidator,
        RequestStack $requestStack
    ) {
        $this->cloudTranslationRepository = $cloudTranslationRepository;
        $this->cloudVisionImageValidator = $cloudVisionImageValidator;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function validate($value, Constraint $constraint)
    {
        if (\is_null($value)) {
            return;
        }

        $this->cloudVisionImageValidator->validate($value, 'LABEL_DETECTION');

        if (\count($this->cloudVisionImageValidator->getViolations()) > 0) {
            $this->context
                ->buildViolation(
                    $this->cloudTranslationRepository->getSingleEntry(
                        'validators.'.$this->requestStack->getCurrentRequest()->getLocale().'.yaml',
                        $this->requestStack->getCurrentRequest()->getLocale(),
                        $constraint->message
                    )->getValue()
                )->addViolation();
        }
    }
}
