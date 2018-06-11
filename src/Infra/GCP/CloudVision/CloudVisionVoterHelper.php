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

namespace App\Infra\GCP\CloudVision;

use App\Infra\GCP\CloudVision\Interfaces\CloudVisionVoterHelperInterface;

/**
 * Class CloudVisionVoterHelper.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionVoterHelper implements CloudVisionVoterHelperInterface
{
    /**
     * @var array
     */
    private $forbiddenLabels = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $forbiddenLabels)
    {
        $this->forbiddenLabels = $forbiddenLabels;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(string $label): bool
    {
        return !in_array($label, $this->forbiddenLabels);
    }
}
