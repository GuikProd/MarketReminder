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

namespace App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;

/**
 * Interface CloudTranslationValidatorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationValidatorInterface
{
    /**
     * @param CloudTranslationInterface $cloudTranslationItem
     * @param array                     $newValues
     *
     * @return array
     */
    public function validate(CloudTranslationInterface $cloudTranslationItem, array $newValues): array;
}
