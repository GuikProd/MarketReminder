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

namespace App\UI\Form\DataTransformer;

use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class StockCreationTagsTransformer.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationTagsTransformer implements StockCreationTagsTransformerInterface, DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (\is_array($value) && \count(array_values($value)) > 1) {
            return implode(", ", $value);
        }

        return null ?: $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!\is_string($value)) {
            return [];
        }

        $tags = explode(', ', $value);

        return $tags;
    }
}
