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

namespace App\Infra\GCP\CloudTranslation\Helper\Validator;

use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationViolationInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationViolationListInterface;

/**
 * Class CloudTranslationViolationList.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationViolationList implements CloudTranslationViolationListInterface, \IteratorAggregate
{
    /**
     * @var CloudTranslationViolationInterface[]
     */
    private $violations = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $violations = [])
    {
        foreach ($violations as $violation) {
            $this->violations[] = $violation;
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->violations);
    }
}
