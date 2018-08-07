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

namespace App\UI\Form\Extension\Interfaces;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;

/**
 * Interface CloudTranslationTypeExtensionInterface.
 * 
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudTranslationTypeExtensionInterface
{
    /**
     * CloudTranslationTypeExtensionInterface constructor.
     *
     * @param CloudTranslationRepositoryInterface $repository
     */
    public function __construct(CloudTranslationRepositoryInterface $repository);
}
