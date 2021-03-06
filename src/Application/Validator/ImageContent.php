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

use Symfony\Component\Validator\Constraint;

/**
 * Class ImageContent.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class ImageContent extends Constraint
{
    /**
     * @var string
     */
    public $message = 'image.forbidden_content';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return ImageContentValidator::class;
    }
}
