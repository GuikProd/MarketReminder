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

/**
 * Class CloudTranslationViolation.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationViolation implements CloudTranslationViolationInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $context;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $message,
        string $context
    ) {
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): string
    {
        return $this->context;
    }
}
