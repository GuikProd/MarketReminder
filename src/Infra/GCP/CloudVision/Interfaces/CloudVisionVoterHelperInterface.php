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

namespace App\Infra\GCP\CloudVision\Interfaces;

/**
 * Interface CloudVisionVoterHelperInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface CloudVisionVoterHelperInterface
{
    /**
     * CloudVisionVoterHelperInterface constructor.
     *
     * @param array $forbiddenLabels
     */
    public function __construct(array $forbiddenLabels);

    /**
     * Allow to vote about a label.
     *
     * @param string $label The label which need to receive a vote.
     *
     * @return void
     */
    public function vote(string $label): void;

    /**
     * Return the decision about a label.
     *
     * @return bool
     */
    public function isLabelAuthorized(): bool;
}
