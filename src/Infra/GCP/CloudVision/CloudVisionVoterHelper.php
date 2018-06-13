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
 * This class is used in order to give a vote on a given label.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudVisionVoterHelper implements CloudVisionVoterHelperInterface
{
    /**
     * The labels which are forbidden.
     *
     * (This array is populated during instantiation)
     *
     * @var array
     */
    private $forbiddenLabels = [];

    /**
     * Contain the decision about the current label.
     *
     * @var bool
     */
    private $decision = true;

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
    public function vote(string $label): void
    {
        $this->decision = in_array($label, $this->forbiddenLabels);
    }

    /**
     * {@inheritdoc}
     */
    public function isLabelAuthorized(): bool
    {
        return !$this->decision;
    }
}
