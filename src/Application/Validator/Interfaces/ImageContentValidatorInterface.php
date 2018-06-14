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

namespace App\Application\Validator\Interfaces;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudVision\Validator\Interfaces\CloudVisionImageValidatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Interface ImageContentValidatorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface ImageContentValidatorInterface
{
    /**
     * ImageContentValidatorInterface constructor.
     *
     * @param CloudTranslationRepositoryInterface $cloudTranslationRepository
     * @param CloudVisionImageValidatorInterface $cloudVisionImageValidator
     * @param RequestStack $requestStack
     */
    public function __construct(
        CloudTranslationRepositoryInterface $cloudTranslationRepository,
        CloudVisionImageValidatorInterface $cloudVisionImageValidator,
        RequestStack $requestStack
    );
}
